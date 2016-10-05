<?php
namespace Blog\Controller
{
    use Silex\Application;
    use Silex\ControllerProviderInterface;
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
            $posts = ($postModel->getPosts($page));
            return $app['twig']->render('home.twig',['posts' => $posts, 'page' => $page]);
        }

        public function post( Application $app )
        {
            $postModel = $app['postModel'];
            $postSlug = $app['request']->get( 'slug' );
            $post = $postModel->getPost($postSlug);

            if (!$post) {
                throw new NotFoundHttpException();
            }

            return $app['twig']->render('post.twig',['post' => $post]);
        }
    }
}
