<?php

namespace App\Controller\Setting;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Region;
use App\Entity\Division;
use App\Entity\Subdivision;
use App\Form\CityType;
use App\Form\CountryType;
use App\Form\MenuType;
use App\Form\RegionType;
use App\Form\DivisionType;
use App\Form\SubdivisionType;

use App\Form\TaxType;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\RegionRepository;
use App\Repository\DivisionRepository;
use App\Repository\SubdivisionRepository;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Error\LoaderError;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Expression\Binary\GreaterEqualBinary;
use Twig\Node\ForNode;
use Twig_Node_Block;
use Twig_Node_Expression_Call;
use Twig_Node_Expression_Unary_Not;
use Twig_NodeVisitor_Optimizer;

/**
 * Controller used to manage Regional Settings.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class SettingRegionalController extends AbstractController
{
    /**
     * Lists all Countries .
     *
     *
     * @Route("{_locale}/country", methods={"GET"}, name="country")
     *
     */
    public function showCountry(CountryRepository $countries): Response
    {
        $userCountries = $countries->findBy(['user' => $this->getUser()]);

        return $this->render('setting/show_country.html.twig', ['countries' => $userCountries]);
    }

    /**
     * Creates a new Country   entity.
     *
     * @Route("{_locale}/country/new", methods={"GET", "POST"}, name="country_new")
     *
     */
    public function newCountry(Request $request, Country $country = null, ObjectManager $em): Response
    {
        if(!$country){$country = new Country();}

        // On Instancie le formulaire
        $form = $this->createForm(CountryType::class, $country)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On prépare l'objet à persister
            $country->setCreatedAt(new\DateTime());
            $country->setUser($this->getUser());

            $em->persist($country);
            $em->flush();

            $this->addFlash('success', 'country.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('country_new');
            }

            return $this->redirectToRoute('country');
        }

        return $this->render('setting/edit_country.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing journal entity.
     *
     * @Route("{_locale}/country/{id<\d+>}/edit",methods={"GET", "POST"}, name="country_edit")
     *
     */
    public function editCountry(Request $request, Country $country, ObjectManager $em): Response
    {
        $form = $this->createForm(CountryType::class, $country)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'country.updated_successfully');

            return $this->redirectToRoute('country');
        }

        return $this->render('setting/edit_country.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Country entity.
     *
     * @Route("/country/{id}/delete", methods={"GET", "POST"}, name="country_delete")
     *
     */
    public function deleteCountry(Request $request, Country $country, ObjectManager $em): Response
    {
        $em->remove($country);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('country');
    }


    /**
     * Lists all Divisions .
     *
     *
     * @Route("{_locale}/division", methods={"GET"}, name="division")
     *
     */
    public function showDivision(DivisionRepository $divisions): Response
    {
        $userDivisions = $divisions->findBy(['user' => $this->getUser()]);

        return $this->render('setting/show_division.html.twig', ['divisions' => $userDivisions]);
    }

    /**
     * Creates a new Division   entity.
     *
     * @Route("{_locale}/division/new", methods={"GET", "POST"}, name="division_new")
     *
     */
    public function newDivision(Request $request, Division $division = null, ObjectManager $em): Response
    {
        if(!$division){$division = new Division();}

        // On Instancie le formulaire
        $form = $this->createForm(DivisionType::class, $division)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On prépare l'objet à persister
            $division->setCreatedAt(new\DateTime());
            $division->setUser($this->getUser());

            $em->persist($division);
            $em->flush();

            $this->addFlash('success', 'division.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('division_new');
            }

            return $this->redirectToRoute('division');
        }

        return $this->render('setting/edit_division.html.twig', [
            'division' => $division,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing division entity.
     *
     * @Route("{_locale}/division/{id<\d+>}/edit",methods={"GET", "POST"}, name="division_edit")
     *
     */
    public function editDivision(Request $request, Division $division, ObjectManager $em): Response
    {
        $form = $this->createForm(DivisionType::class, $division)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'division.updated_successfully');

            return $this->redirectToRoute('division');
        }

        return $this->render('setting/edit_division.html.twig', [
            'division' => $division,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Deletes a Division entity.
     *
     * @Route("/division/{id}/delete", methods={"GET", "POST"}, name="division_delete")
     *
     */
    public function deleteDivision(Request $request, Division $division, ObjectManager $em): Response
    {
        $em->remove($division);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('division');
    }

    /**
     * Lists all Region .
     *
     *
     * @Route("{_locale}/region", methods={"GET"}, name="region")
     *
     */
    public function showRegion(RegionRepository $regions): Response
    {
        $userRegions = $regions->findAll();

        return $this->render('setting/show_region.html.twig', ['regions' => $userRegions]);
    }

    /**
     * Creates a new Regions   entity.
     *
     * @Route("{_locale}/region/new", methods={"GET", "POST"}, name="region_new")
     *
     */
    public function newRegion(Request $request, Region $region = null, ObjectManager $em): Response
    {
        if(!$region){$region = new Region();}

        // On Instancie le formulaire
        $form = $this->createForm(RegionType::class, $region)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $region->setCreatedAt(new\DateTime());
            $region->setUser($this->getUser());

            $em->persist($region);
            $em->flush();

            $this->addFlash('success', 'region.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('region_new');
            }

            return $this->redirectToRoute('region');
        }

        return $this->render('setting/edit_region.html.twig', [
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing journal entity.
     *
     * @Route("{_locale}/region/{id<\d+>}/edit",methods={"GET", "POST"}, name="region_edit")
     *
     */
    public function editRegion(Request $request, Region $region, ObjectManager $em): Response
    {
        $form = $this->createForm(RegionType::class, $region)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'region.updated_successfully');

            return $this->redirectToRoute('region');
        }

        return $this->render('setting/edit_region.html.twig', [
            'region' => $region,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Region entity.
     *
     * @Route("/region/{id}/delete", methods={"GET", "POST"}, name="region_delete")
     *
     */
    public function deleteRegion(Request $request, Region $region, ObjectManager $em): Response
    {
        $em->remove($region);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('region');
    }

    /**
     * Lists all SubDivision .
     *
     *
     * @Route("{_locale}/subdivision", methods={"GET"}, name="subdivision")
     */
    public function showSubDivision(SubdivisionRepository $subDivisions): Response
    {
        $userSubDivisions = $subDivisions->findAll();

        return $this->render('setting/show_subdivision.html.twig', ['subdivisions' => $userSubDivisions]);
    }

    /**
     * Creates a new SubDivision   entity.
     *
     * @Route("{_locale}/subdivision/new", methods={"GET", "POST"}, name="subdivision_new")
     *
     */
    public function newSubDivision(Request $request, Subdivision $subdivision = null, ObjectManager $em): Response
    {
        if(!$subdivision){$subdivision = new Subdivision();}

        // On Instancie le formulaire
        $form = $this->createForm(SubdivisionType::class, $subdivision)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();
            // On prépare l'objet à persister
            $subdivision->setCreatedAt(new\DateTime());
            $subdivision->setUser($this->getUser());
            //$log->setBranch($branch);
            //$season->setIsEnabled(true);

            $em->persist($subdivision);
            $em->flush();

            $this->addFlash('success', 'subdivision.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('subdivision_new');
            }

            return $this->redirectToRoute('subdivision');
        }

        return $this->render('setting/edit_subdivision.html.twig', [
            'subdivision' => $subdivision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing division entity.
     *
     * @Route("{_locale}/subdivision/{id<\d+>}/edit",methods={"GET", "POST"}, name="subdivision_edit")
     *
     */
    public function editSubDivision(Request $request, Subdivision $subdivision, ObjectManager $em): Response
    {
        $form = $this->createForm(SubdivisionType::class, $subdivision)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'subdivision.updated_successfully');

            return $this->redirectToRoute('subdivision');
        }

        return $this->render('setting/edit_subdivision.html.twig', [
            'subdivision' => $subdivision,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Subdivision entity.
     *
     * @Route("/subdivision/{id}/delete", methods={"GET", "POST"}, name="subdivision_delete")
     *
     */
    public function deleteSubdivision(Request $request, Subdivision $subDivision, ObjectManager $em): Response
    {
        $em->remove($subDivision);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('subdivision');
    }

    /**
     * Lists all Countries .
     *
     *
     * @Route("{_locale}/city", methods={"GET"}, name="city")
     *
     */
    public function showCity(CityRepository $cities): Response
    {
        $userCities = $cities->findBy(['user' => $this->getUser()]);

        return $this->render('setting/show_city.html.twig', ['cities' => $userCities]);
    }

    /**
     * Creates a new Country   entity.
     *
     * @Route("{_locale}/city/new", methods={"GET", "POST"}, name="city_new")
     *
     */
    public function newCity(Request $request, City $city = null, ObjectManager $em): Response
    {
        if(!$city){$city = new City();}

        // On Instancie le formulaire
        $form = $this->createForm(CityType::class, $city)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On prépare l'objet à persister
            $city->setCreatedAt(new\DateTime());
            $city->setUser($this->getUser());

            $em->persist($city);
            $em->flush();

            $this->addFlash('success', 'city.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('city_new');
            }

            return $this->redirectToRoute('city');
        }

        return $this->render('setting/edit_city.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing journal entity.
     *
     * @Route("{_locale}/city/{id<\d+>}/edit",methods={"GET", "POST"}, name="city_edit")
     *
     */
    public function editCity(Request $request, City $city, ObjectManager $em): Response
    {
        $form = $this->createForm(CityType::class, $city)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'city.updated_successfully');

            return $this->redirectToRoute('city');
        }

        return $this->render('setting/edit_city.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Country entity.
     *
     * @Route("/city/{id}/delete", methods={"GET", "POST"}, name="city_delete")
     *
     */
    public function deleteCity(Request $request, City $city, ObjectManager $em): Response
    {
        $em->remove($city);
        $em->flush();

        $this->addFlash('success', 'Success!');

        return $this->redirectToRoute('city');
    }

}