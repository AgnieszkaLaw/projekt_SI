<?php
/**
 * Event controller.
 */

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\Type\EventType;
use App\Service\EventServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class EventController.
 */
#[Route('/event')]
class EventController extends AbstractController
{
    /**
     * Event service.
     */
    private EventServiceInterface $eventService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param EventServiceInterface $eventService Event service
     * @param TranslatorInterface   $translator   Translator
     */
    public function __construct(EventServiceInterface $eventService, TranslatorInterface $translator)
    {
        $this->eventService = $eventService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(
        name: 'event_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        /** @var User $user */
        $user = $this->getUser();
        $pagination = $this->eventService->getPaginatedList(
            $request->query->getInt('page', 1),
            $user,
            $filters
        );

        return $this->render('event/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Event $event Event entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'event_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    #[IsGranted('VIEW', subject: 'event')]
    public function show(Event $event): Response
    {
        return $this->render(
            'event/show.html.twig',
            ['event' => $event]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'event_create', methods: 'GET|POST')]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $event = new Event();
        $event->setAuthor($user);
        $form = $this->createForm(
            EventType::class,
            $event,
            ['action' => $this->generateUrl('event_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventService->save($event);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Event   $event   Event entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'event_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('EDIT', subject: 'event')]
    public function edit(Request $request, Event $event): Response
    {

        $form = $this->createForm(
            EventType::class,
            $event,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('event_edit', ['id' => $event->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventService->save($event);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('event_index');
        }

        return $this->render(
            'event/edit.html.twig',
            [
                'form' => $form->createView(),
                'event' => $event,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Event   $event   Event entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'event_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('DELETE', subject: 'event')]
    public function delete(Request $request, Event $event): Response
    {

        $form = $this->createForm(
            FormType::class,
            $event,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('event_delete', ['id' => $event->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->eventService->delete($event);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('event_index');
        }

        return $this->render(
            'event/delete.html.twig',
            [
                'form' => $form->createView(),
                'event' => $event,
            ]
        );
    }

    /**
     * Get filters from request.
     *
     * @param Request $request HTTP request
     *
     * @return array<string, int> Array of filters
     *
     * @psalm-return array{category_id: int, tag_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        return $filters;
    }
}
