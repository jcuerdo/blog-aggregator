<?php
namespace Blog\Controller
{
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Request;

    class ApiController implements ControllerProviderInterface
    {
        public function connect( Application $app )
        {
            $indexController = $app['controllers_factory'];

            $indexController->get("/", array( $this, 'index' ) )
                ->bind( 'api_home' );

            $indexController->get("/{slug}", array( $this, 'post' ) )
                ->bind( 'api_post' );
            
            $app->after(function (Request $request, Response $response) {
                $response->headers->set('Access-Control-Allow-Origin', '*');
            });

            $app->finish(function (Request $request, Response $response) use ($app) {
                /**
                 * @var \Blog\Model\Visit $visitModel
                 */
                $visitModel = $app['visitModel'];
                $visitModel->insert( $request->getRequestUri(),$request->headers->get('User-Agent') , $request->getClientIp());
            });

            return $indexController;
        }

        public function index( Application $app )
        {
            $page = $app['request']->get( 'page' ) ? : 0;
            $postModel = $app['postModel'];
            $searchTerm = $app['request']->get( 's' ) ? : '';
            if($searchTerm){
                $posts = ($postModel->searchPosts($searchTerm, $page));
            } else{
                $posts = ($postModel->getPosts($page,20));
            }

            if (!$posts) {
                throw new NotFoundHttpException('No se han encontrado resultados');
            }

            return new JsonResponse(
                [
                    'posts' => $posts,
                    'page' => $page,
                    'searchTerm' => $searchTerm
                ],
                200
            );
        }

        public function post( Application $app )
        {
            $postModel = $app['postModel'];
            $postSlug = $app['request']->get( 'slug' );
            $post = $postModel->getPost($postSlug);

            if (!$post) {
                throw new NotFoundHttpException();
            }

            return new JsonResponse(
                [
                    'post' => $post
                ],
                200
            );
        }
    }
}
