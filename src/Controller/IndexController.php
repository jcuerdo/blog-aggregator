<?php
namespace Blog\Controller
{
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    class IndexController implements ControllerProviderInterface
    {
        public function connect( Application $app )
        {
            $indexController = $app['controllers_factory'];

            $indexController->get("/", array( $this, 'index' ) )
                ->bind( 'home' );

            $indexController->get("/{slug}", array( $this, 'post' ) )
                ->bind( 'post' );

            return $indexController;
        }

        public function index( Application $app )
        {
            $page = $app['request']->get( 'page' ) ? : 0;
            $postModel = $app['postModel'];
            $searchTerm = $app['request']->get( 's' ) ? : '';
            $lastPosts = ($postModel->getPosts($page));
            if($searchTerm){
                $posts = ($postModel->searchPosts($searchTerm, $page));
            } else{
                $posts = ($postModel->getPosts($page));
            }

            if (!$posts) {
                throw new NotFoundHttpException('No results');
            }

            return new Response($app['twig']->render('home.twig',[
                    'lastPosts' => $lastPosts,
                    'posts' => $posts,
                    'page' => $page]
            ), 200, [
                'Cache-Control' => 's-maxage=500'
            ]);

            return ;
        }

        public function post( Application $app )
        {
            $postModel = $app['postModel'];
            $postSlug = $app['request']->get( 'slug' );
            $post = $postModel->getPost($postSlug);
            $lastPosts = ($postModel->getPosts());

            if (!$post) {
                throw new NotFoundHttpException();
            }

            return new Response($app['twig']->render('post.twig', [
                    'lastPosts' => $lastPosts,
                    'post' => $post
                ]
            ), 200, [
                'Cache-Control' => 's-maxage=10000000'
            ]);
        }
    }
}
