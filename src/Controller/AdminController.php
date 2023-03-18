<?php
namespace Blog\Controller
{
    use Blog\Library\Gpt;
    use Blog\Model\Rss;
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Response;

    class AdminController implements ControllerProviderInterface
    {
        public function connect( Application $app )
        {
            $adminController = $app['controllers_factory'];

            $adminController->get("/", array( $this, 'index' ) )
                ->bind( 'admin' );

            $adminController->get("/rss", array( $this, 'adminRss' ) )
                ->bind( 'adminRss' );

            $adminController->get("/post", array( $this, 'adminPosts' ) )
                ->bind( 'adminPosts' );

            $adminController->get("/post/new", array( $this, 'newPost' ) )
                ->bind( 'adminNewPost' );

            $adminController->get("/post/{slug}/edit", array( $this, 'editPost' ) )
                ->bind( 'editPost' );

            $adminController->get("/post/{slug}/editFirebase", array( $this, 'editFirebaseNotification' ) )
                ->bind( 'editFirebaseNotification' );

            $adminController->post("/post/{slug}/sendFirebase", array( $this, 'sendFirebaseNotification' ) )
                ->bind( 'sendFirebaseNotification' );

            $adminController->get("/deleteRss", array( $this, 'deleteRss' ) )
                ->bind( 'deleteRss' );

            $adminController->get("/addRss", array( $this, 'addRss' ) )
                ->bind( 'addRss' );

            $adminController->post("/addPost", array( $this, 'addPost' ) )
                ->bind( 'addPost' );

            $adminController->post("/savePost", array( $this, 'savePost' ) )
                ->bind( 'savePost' );


            $adminController->get("/deletePost", array( $this, 'deletePost' ) )
                ->bind( 'deletePost' );

            $adminController->post("/gpt", array( $this, 'gpt' ) )
                ->bind( 'gpt' );

            return $adminController;
        }

        public function index( Application $app )
        {
            /**
             * @var Rss $rssModel
             */
            $rssModel = $app['rssModel'];
            $rsss = $rssModel->getAll();

            return new Response($app['twig']->render('admin.twig',[
                    'rsss' => $rsss,
                ]
            ), 200);
        }

        public function adminRss( Application $app )
        {
            /**
             * @var Rss $rssModel
             */
            $rssModel = $app['rssModel'];
            $rsss = $rssModel->getAll();

            return new Response($app['twig']->render('admin_rss.twig',[
                    'rsss' => $rsss,
                ]
            ), 200);
        }

        public function adminPosts( Application $app )
        {
            $page = $app['request']->get( 'page' ) ? : 0;
            $postModel = $app['postModel'];
            $searchTerm = $app['request']->get( 's' ) ? : '';
            if($searchTerm){
                $posts = ($postModel->searchPosts($searchTerm, $page));
            } else{
                $posts = ($postModel->getPosts($page));
            }

            return new Response($app['twig']->render('admin_posts.twig',[
                    'posts' => $posts,
                    'page' => $page,
                    'searchTerm' => $searchTerm
                ]
            ), 200);
        }

        public function deleteRss( Application $app )
        {
            $url = $app['request']->get( 'url' );
            /**
             * @var Rss $rssModel
             */
            $rssModel = $app['rssModel'];
            $rssModel->delete($url);

            return $app->redirect($app["url_generator"]->generate("adminRss"));
        }

        public function addRss( Application $app )
        {
            $url = $app['request']->get( 'url' );
            /**
             * @var Rss $rssModel
             */
            $rssModel = $app['rssModel'];
            $rssModel->insert($url);

            return $app->redirect($app["url_generator"]->generate("adminRss"));
        }

        public function addPost( Application $app )
        {
            $title = $app['request']->get( 'title' );
            $slug = $app['request']->get( 'slug' );
            $content = $app['request']->get( 'content' );
            /**
             * @var Rss $rssModel
             */
            $postModel = $app['postModel'];
            $slug = $postModel->insertPost($title, null, $slug, $content);

            /**
             * @var \Blog\Library\GoogleClient $googleClient
             */
            $googleClient = $app['google_client'];
            $googleClient->indexUrl($slug);

            $exporter = new \Blog\Twitter\Exporter();
            $exporter->publishPost($title . ' - ' . $app['url'] . '/' . $slug);

            return $app->redirect($app["url_generator"]->generate("adminPosts"));
        }

        public function newPost( Application $app )
        {
            return new Response($app['twig']->render('admin_newpost.twig',[
                ]
            ), 200);
        }

        public function gpt( Application $app )
        {
            /**
             * @var Gpt $gpt
             */
            $gpt = $app['gpt'];

            $content = $app['request']->getContent();

            $result = $gpt->generate($content);

            return new JsonResponse($result, 200);
        }

        public function savePost( Application $app )
        {
            $title = $app['request']->get( 'title' );
            $slug = $app['request']->get( 'slug' );
            $content = $app['request']->get( 'content' );
            /**
             * @var Rss $rssModel
             */
            $postModel = $app['postModel'];
            $postModel->updatePost($title, $content, $slug);

            return $app->redirect($app["url_generator"]->generate("editPost",['slug' => $slug]));
        }

        public function editPost( Application $app )
        {
            $slug = $app['request']->get( 'slug' );
            $postModel = $app['postModel'];
            $post = $postModel->getPost($slug);
            return new Response($app['twig']->render('admin_editpost.twig',[
                'post' => $post
                ]
            ), 200);
        }

        public function editFirebaseNotification( Application $app )
        {
            $slug = $app['request']->get( 'slug' );
            $postModel = $app['postModel'];
            $post = $postModel->getPost($slug);
            return new Response($app['twig']->render('admin_editfirebase.twig',[
                    'post' => $post
                ]
            ), 200);
        }

        public function sendFirebaseNotification( Application $app )
        {
            $slag = $app['request']->get( 'slag' );
            $title = $app['request']->get( 'title' );
            $description = $app['request']->get( 'description' );
            $image = $app['request']->get( 'image' );
            $firebaseKey = $app["firebase_key"];

            if($firebaseKey){
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\n\"condition\" : \"'notifications' in topics\",\n \"notification\" : {\n \t \"title\": \"$title\",\n     \"body\" : \"$description\",\n     \"image\": \"$image\"\n },\n \"data\" : {\n     \"slag\" : \"$slag\"\n }\n}",
                    CURLOPT_HTTPHEADER => array(
                        "Authorization: key=$firebaseKey",
                        "Content-Type: application/json"
                    ),
                ));


                $response = curl_exec($curl);

                curl_close($curl);
            }


            return $app->redirect($app["url_generator"]->generate("adminPosts"));

    }

        public function deletePost( Application $app )
        {
            $slug = $app['request']->get( 'slug' );
            /**
             * @var Rss $rssModel
             */
            $postModel = $app['postModel'];
            $postModel->deletePost($slug);

            return $app->redirect($app["url_generator"]->generate("adminPosts"));     }
    }
}
