<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Helper\FileUploader;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/series', name: 'series_')]
class SerieController extends AbstractController
{
    #[Route('/details/{id}', name: 'details', methods: ['GET', 'POST'], requirements: ['id'=>'\d+'])]
    public function details(int $id, SerieRepository $serieRepository): Response
    {
        $serie = $serieRepository->find($id);
//        dump($id);
//        dump($id);
//        dump($id);
//        dump($id);
        return $this->render('serie/details.html.twig', [
            'serie' => $serie,
        ]);
    }

//    #[Route('', name: 'list', methods: ['GET'])]
//    public function list(SerieRepository $serieRepository): Response
//    {
////        $series = $serieRepository->findAll();
//        $series = $serieRepository->findBy([], ['popularity' => 'DESC'], 30, 0);
//
//        return $this->render('serie/list.html.twig', [
//            'series' => $series
//        ]);
//    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function save(
        Request $request,
        EntityManagerInterface $entityManager,
        SerieRepository $serieRepository,
        int $id = null,
        FileUploader $fileUploader
    ): Response
    {
        $serie = $id ? $serieRepository->find($id) : new Serie();

        if(!$serie) {
            throw $this->createNotFoundException('no such TV show !');
        }
//        $serie = new Serie();
//        $serie
//            ->setBackdrop("backdrop.png")
//            ->setDateCreated(new \DateTime())
//            ->setName("The gentlemen")
//            ->setGenres("Gangsters")
//            ->setVote(8)
//            ->setFirstAirDate(new \DateTime())
//            ->setOverview("Un truc de gangsters")
//            ->setPopularity(450)
//            ->setPoster("poster.png")
//            ->setStatus("returning")
//            ->setTmdbId(1234);
//
//        $serie2 = new Serie();
//        $serie2
//            ->setBackdrop("backdrop.png")
//            ->setDateCreated(new \DateTime())
//            ->setName("The gentlemen")
//            ->setGenres("Gangsters")
//            ->setVote(8)
//            ->setFirstAirDate(new \DateTime())
//            ->setOverview("Un truc de gangsters")
//            ->setPopularity(450)
//            ->setPoster("poster.png")
//            ->setStatus("returning")
//            ->setTmdbId(1234);
//
//        $entityManager->persist($serie);
//        $entityManager->persist($serie2);
//        $entityManager->flush();
//
//        $serie->setName("The gentlewomen");
//        $serie2->setName("The badwomen");
//
//        $entityManager->flush();
//
//        dump($serie);
//        dump($serie2);
//
//        $entityManager->remove($serie);
//        $entityManager->flush();

        $serieForm = $this->createForm(SerieType::class, $serie);

        $serieForm->get('genres')->setData(explode(' / ', $serie->getGenres()));

        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            $backdrop = $serieForm->get('backdrop')->getData();

//            dd($backdrop);
            if($backdrop) {
                $fileName = $fileUploader->upload($backdrop, $this->getParameter('backdrop_path'), $serie->getName());
//                /**
//                 * @var UploadedFile $backdrop
//                 */
//                $fileName = $serie->getName() . '-' . uniqid() . '-' . $backdrop->guessExtension();
//
//                $backdrop->move('../assets/img/backdrops', $fileName);
//
                $serie->setBackdrop($fileName);
            }
//            dd($request);
//            $serie->setDateCreated(new \DateTime());
            $serie->setGenres(implode(' / ', $serieForm->get('genres')->getData()));



            $entityManager->persist($serie);
            $entityManager->flush();

            $this->addFlash('success', 'TV show' . $serie->getName() . ' has been updated');

            return $this->redirectToRoute('series_details', ['id' => $serie->getId()]);
        }

        return $this->render('serie/save.html.twig', [
            'serieForm' => $serieForm,
            'serieId' => $id,
        ]);

    }

    #[Route('/{page}', name: 'list', requirements: ['page' => '\d+'], methods: ['GET'])]
    public function list2(SerieRepository $repository, int $page = 1, int $offset = 50): Response
    {
        $maxPage = ceil($repository->count([]) / $offset);    // S'assurer que la page min est 1 et page max est page max

        if ($page < 1) {
            return $this->redirectToRoute('series_list', ['page' => 1]);
        }
        if ($page > $maxPage) {
            return $this->redirectToRoute('series_list', ['page' => $maxPage]);
        }

        $series = $repository->findWithPagination($page);    //TODO Renvoyer la liste des séries

        return $this->render('serie/list.html.twig', [
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $maxPage,
        ]);

    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function delete(Request $request, Serie $serie, EntityManagerInterface $entityManager, SerieRepository $serieRepository): Response
    {
        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('success', 'TV show' . $serie->getName() . ' has been removed');

        return $this->redirectToRoute('series_list');
    }
}
