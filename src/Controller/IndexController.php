<?php
namespace Blog\Controller
{
    use Silex\Application;
    use Silex\ControllerProviderInterface;

    class IndexController implements ControllerProviderInterface
    {
        public function connect( Application $app )
        {
            $indexController = $app['controllers_factory'];

            $indexController->get("/", array( $this, 'index' ) )
                ->bind( 'home' );

            return $indexController;
        }

        public function index( Application $app )
        {
            $postModel = $app['postModel'];
            $posts = $postModel->getPosts();
            return $app['twig']->render('home.twig',['posts' => $posts]);
        }
    }
}
