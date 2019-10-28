<?php
namespace Blog\Controller
{
    use Silex\Application;
    use Silex\ControllerProviderInterface;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    class ApiController implements ControllerProviderInterface
    {
        public function connect( Application $app )
        {
            $indexController = $app['controllers_factory'];

            $indexController->get("/", array( $this, 'api_index' ) )
                ->bind( 'api_home' );

            $indexController->get("/{slug}", array( $this, 'api_post' ) )
                ->bind( 'api_post' );

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
                $posts = ($postModel->getPosts($page));
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
