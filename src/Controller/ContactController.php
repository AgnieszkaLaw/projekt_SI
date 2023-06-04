<?php
/**
 * Contact controller.
 */

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Form\Type\ContactType;
use App\Service\ContactServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ContactController.
 */
#[Route('/contact')]
class ContactController extends AbstractController
{
    /**
     * Contact service.
     */
    private ContactServiceInterface $contactService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param ContactServiceInterface $contactService Contact service
     * @param TranslatorInterface  $translator  Translator
     */
    public function __construct(ContactServiceInterface $contactService, TranslatorInterface $translator)
    {
        $this->contactService = $contactService;
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
        name: 'contact_index',
        methods: 'GET'
    )]
    public function index(Request $request): Response
    {
        $filters = $this->getFilters($request);
        /** @var User $user */
        $user = $this->getUser();
        $pagination = $this->contactService->getPaginatedList(
            $request->query->getInt('page', 1),
            $user,
            $filters
        );

        return $this->render('contact/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Contact $contact Contact entity
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'contact_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET',
    )]
    #[IsGranted('VIEW', subject: 'contact')]
    public function show(Contact $contact): Response
    {
        if ($contact->getAuthor() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );
            return $this->redirectToRoute('contact_index');
        }

        return $this->render(
            'contact/show.html.twig',
            ['contact' => $contact]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     */
    #[Route('/create', name: 'contact_create', methods: 'GET|POST', )]
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $contact = new Contact();
        $contact->setAuthor($user);
        $form = $this->createForm(
            ContactType::class,
            $contact,
            ['action' => $this->generateUrl('contact_create')]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactService->save($contact);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/create.html.twig',  ['form' => $form->createView()]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Contact    $contact    Contact entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'contact_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('EDIT', subject: 'contact')]
    public function edit(Request $request, Contact $contact): Response
    {
        if ($contact->getAuthor() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );
            return $this->redirectToRoute('contact_index');
        }

        $form = $this->createForm(
            ContactType::class,
            $contact,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('contact_edit', ['id' => $contact->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactService->save($contact);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('contact_index');
        }

        return $this->render(
            'contact/edit.html.twig',
            [
                'form' => $form->createView(),
                'contact' => $contact,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Contact    $contact    Contact entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'contact_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('DELETE', subject: 'contact')]
    public function delete(Request $request, Contact $contact): Response
    {
        if ($contact->getAuthor() !== $this->getUser()) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.record_not_found')
            );
            return $this->redirectToRoute('contact_index');
        }

        $form = $this->createForm(
            FormType::class,
            $contact,
            [
                'method' => 'DELETE',
                'action' => $this->generateUrl('contact_delete', ['id' => $contact->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactService->delete($contact);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('contact_index');
        }

        return $this->render(
            'contact/delete.html.twig',
            [
                'form' => $form->createView(),
                'contact' => $contact,
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
     * @psalm-return array{tag_id: int}
     */
    private function getFilters(Request $request): array
    {
        $filters = [];
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');

        return $filters;
    }
}
