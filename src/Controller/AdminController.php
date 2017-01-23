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

            $adminController->get("/deleteRss", array( $this, 'deleteRss' ) )
                ->bind( 'deleteRss' );

            $adminController->get("/addRss", array( $this, 'addRss' ) )
                ->bind( 'addRss' );

            $adminController->get("/addPost", array( $this, 'addPost' ) )
                ->bind( 'addPost' );

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

        public function deleteRss( Application $app )
        {
            $url = $app['request']->get( 'url' );
            /**
             * @var Rss $rssModel
             */
            $rssModel = $app['rssModel'];
            $rssModel->delete($url);

            return $app->redirect($app["url_generator"]->generate("admin"));
        }

        public function addRss( Application $app )
        {
            $url = $app['request']->get( 'url' );
            /**
             * @var Rss $rssModel
             */
            $rssModel = $app['rssModel'];
            $rssModel->insert($url);

            return $app->redirect($app["url_generator"]->generate("admin"));
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

            return $app->redirect($app["url_generator"]->generate("admin"));
        }
    }
}