<?php
namespace Blog\Controller
{
    use Blog\Model\Rss;
    use Silex\Application;
    use Silex\ControllerProviderInterface;
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
            $postModel->insertPost($title, null, $slug, $content);

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