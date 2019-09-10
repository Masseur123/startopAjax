<?php

namespace App\Controller;

// Housing 
use App\Entity\HousingOption;
use App\Entity\HousingType;
use App\Entity\Housing;
use App\Entity\StayType;

// Setting 
use App\Entity\TagCategory;
use App\Entity\Tag;
use App\Entity\Meal;
use App\Entity\Season;

// Equipment 
use App\Entity\Equipment;
use App\Entity\EquipmentCategory;
use App\Entity\Extra;
use App\Entity\ExtraCategory;

// Pricing 
use App\Entity\PricingPlan;
use App\Entity\Pricing;
use App\Entity\TagPlanConfiguration;
use App\Repository\HousingOptionRepository;
use App\Repository\HousingTypeRepository;
use App\Repository\StayTypeRepository;
use App\Repository\MealRepository;
use App\Repository\SeasonRepository;
use App\Repository\HousingRepository;
use App\Repository\TagCategoryRepository;
use App\Repository\TagRepository;
use App\Repository\EquipmentRepository;
use App\Repository\EquipmentCategoryRepository;
use App\Repository\ExtraRepository;
use App\Repository\ExtraCategoryRepository;
use App\Repository\PricingPlanRepository;
use App\Repository\PricingRepository;

use App\Form\HousingOptionType;
use App\Form\HousingTypeType;
use App\Form\StayTypeType;
use App\Form\MealType;
use App\Form\SeasonType;
use App\Form\HousingfType;
use App\Form\TagCategoryType;
use App\Form\TagType;
use App\Form\EquipmentType;
use App\Form\EquipmentCategoryType;
use App\Form\ExtraType;
use App\Form\ExtraCategoryType;
use App\Form\PricingPlanType;
use App\Form\PricingType;
use App\Form\TagPlanConfigType;
use App\Repository\TagPlanConfigurationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Symfony\Component\Stopwatch\StopwatchPeriod;
use Twig\Error\RuntimeError;
use Twig\Node\Expression\Binary\AbstractBinary;
use Twig\Node\Expression\Binary\EqualBinary;
use Twig\Node\Expression\FunctionExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Expression\ParentExpression;
use Twig\Node\MacroNode;
use Twig\Node\TextNode;
use Twig\Profiler\Dumper\BaseDumper;
use Twig\Profiler\Dumper\BlackfireDumper;
use Twig\Sandbox\SecurityError;
use Twig\Util\TemplateDirIterator;
use Twig_Extension_Profiler;
use Twig_TokenParser_Do;
use Twig_TokenParser_With;

/**
 * Controller used to manage Housing in WebAppSoft.
 *
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */

class HousingController extends AbstractController
{
    /**
     * Lists all Housing Option.
     *
     *
     * @Route("/h-option", methods={"GET"}, name="housing_h-option")
     * 
     */
    public function showHousingOption(HousingOptionRepository $housingoptions): Response
    {
        $userHousingOptions = $housingoptions->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_h-option.html.twig', ['housingoptions' => $userHousingOptions]);
    }

    /**
     * Creates a new Housing Option entity.
     *
     * @Route("/h-option/new", methods={"GET", "POST"}, name="housing_h-option_new")
     *
     */
    public function newHousingOption(Request $request, HousingOption $housingoption = null, ObjectManager $em): Response
    {
        if(!$housingoption){$housingoption = new HousingOption();}

        // On Instancie le formulaire 
        $form = $this->createForm(HousingOptionType::class, $housingoption)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $housingoption->setCreatedAt(new\DateTime());
            $housingoption->setUser($this->getUser());
            $housingoption->setIsEnabled(true);

            $em->persist($housingoption);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'housingoption.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_h-option_new');
            }

            return $this->redirectToRoute('housing_h-option');
        }

        return $this->render('housing/edit_h-option.html.twig', [
            'housingoption' => $housingoption,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Housing Option entity.
     *
     * @Route("/h-option/{id<\d+>}/edit",methods={"GET", "POST"}, name="housing_h-option_edit")
     * 
     */
    public function editHousingOption(Request $request, HousingOption $housingoption, ObjectManager $em): Response
    {
        $form = $this->createForm(HousingOptionType::class, $housingoption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'housingoption.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_h-option_new');
            }

            return $this->redirectToRoute('housing_h-option');
        }

        return $this->render('housing/edit_h-option.html.twig', [
            'housingoption' => $housingoption,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Housing Option entity.
     *
     * @Route("/h-option/{id}/delete", methods={"GET", "POST"}, name="housing_h-option_delete")
     * 
     */
    public function deleteHousingOption(Request $request, HousingOption $housingoption, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('setting_civility');
        }*/

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getTags()->clear();

        $em->remove($housingoption);
        $em->flush();

        $this->addFlash('success', 'housingoption.deleted_successfully');

        return $this->redirectToRoute('housing_h-option');
    }

    /**
     * Lists all Housing Type.
     *
     *
     * @Route("/h-type", methods={"GET"}, name="housing_h-type")
     * 
     */
    public function showHousingType(HousingTypeRepository $housingtypes): Response
    {
        $userHousingTypes = $housingtypes->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_h-type.html.twig', ['housingtypes' => $userHousingTypes]);
    }

    /**
     * Creates a new Housing Type entity.
     *
     * @Route("/h-type/new", methods={"GET", "POST"}, name="housing_h-type_new")
     *
     */
    public function newHousingType(Request $request, HousingType $housingtype = null, ObjectManager $em): Response
    {
        if(!$housingtype){$housingtype = new HousingType();}

        // On Instancie le formulaire 
        $form = $this->createForm(HousingTypeType::class, $housingtype)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $housingtype->setCreatedAt(new\DateTime());
            $housingtype->setUser($this->getUser());
            $housingtype->setIsEnabled(true);

            $em->persist($housingtype);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'housingtype.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_h-type_new');
            }

            return $this->redirectToRoute('housing_h-type');
        }

        return $this->render('housing/edit_h-type.html.twig', [
            'housingtype' => $housingtype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Housing Type entity.
     *
     * @Route("/h-type/{id<\d+>}/edit",methods={"GET", "POST"}, name="housing_h-type_edit")
     * 
     */
    public function editHousingType(Request $request, HousingType $housingtype, ObjectManager $em): Response
    {
        $form = $this->createForm(HousingTypeType::class, $housingtype)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'housingtype.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_h-type_new');
            }


            return $this->redirectToRoute('housing_h-type');
        }

        return $this->render('housing/edit_h-type.html.twig', [
            'housingtype' => $housingtype,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Housing Type entity.
     *
     * @Route("/h-type/{id}/delete", methods={"GET", "POST"}, name="housing_h-type_delete")
     * 
     */
    public function deleteHousingType(Request $request, HousingType $housingtype, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('setting_civility');
        }*/

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getTags()->clear();

        $em->remove($housingtype);
        $em->flush();

        $this->addFlash('success', 'housingtype.deleted_successfully');

        return $this->redirectToRoute('housing_h-type');
    }

    /**
     * Lists all StayType.
     *
     *
     * @Route("/staytype", methods={"GET"}, name="housing_staytype")
     *
     */
    public function showStayType(StayTypeRepository $stayTypes): Response
    {
        $userStayType = $stayTypes->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_staytype.html.twig', ['stayTypes' => $userStayType]);
    }

    /**
     * Creates a new Staytype entity.
     *
     * @Route("/staytype/new", methods={"GET", "POST"}, name="housing_staytype_new")
     *
     */
    public function StayTypeNew(Request $request, StayType $stayType = null, ObjectManager $em): Response
    {
        if(!$stayType){$stayType = new StayType();}

        // On Instancie le formulaire
        $form = $this->createForm(StayTypeType::class, $stayType)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $stayType->setCreatedAt(new\DateTime());
            $stayType->setUser($this->getUser());
            //$stayType->setIsEnabled(true);

            $em->persist($stayType);
            $em->flush();

            $this->addFlash('success', 'staytype.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_staytype_new');
            }

            return $this->redirectToRoute('housing_staytype');
        }

        return $this->render('housing/edit_staytype.html.twig', [
            'staytype' => $stayType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing stay type entity.
     *
     * @Route("/staytype/{id<\d+>}/edit",methods={"GET", "POST"}, name="housing_staytype_edit")
     *
     */
    public function editStayType(Request $request, StayType $stayType, ObjectManager $em): Response
    {
        $form = $this->createForm(StayTypeType::class, $stayType)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'staytype.updated_successfully');

            return $this->redirectToRoute('housing_staytype');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_staytype_new');
            }
        }

        return $this->render('housing/edit_staytype.html.twig', [
            'staytype' => $stayType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a StayType entity.
     *
     * @Route("/staytype/{id}/delete", methods={"GET", "POST"}, name="housing_staytype_delete")
     *
     */
    public function StayTypeDelete(Request $request, StayType $stayType, ObjectManager $em): Response
    {

        $em->remove($stayType);
        $em->flush();

        $this->addFlash('success', 'staytype.deleted_successfully');

        return $this->redirectToRoute('housing_staytype');
    }

    /**
     * Lists all Meal.
     *
     *
     * @Route("/meal", methods={"GET"}, name="housing_meal")
     *
     */
    public function showMeal(MealRepository $meals): Response
    {
        $userMeals = $meals->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_meal.html.twig', ['meals' => $userMeals]);
    }

    /**
     * Creates a new meal entity.
     *
     * @Route("/meal/new", methods={"GET", "POST"}, name="housing_meal_new")
     *
     */
    public function newMeal(Request $request, Meal $meal = null, ObjectManager $em): Response
    {
        if(!$meal){$meal = new Meal();}

        // On Instancie le formulaire
        $form = $this->createForm(MealType::class, $meal)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $meal->setCreatedAt(new\DateTime());
            $meal->setUser($this->getUser());
            $meal->setIsEnabled(true);

            $em->persist($meal);
            $em->flush();

            $this->addFlash('success', 'meal .created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_meal_new');
            }

            return $this->redirectToRoute('housing_meal');
        }

        return $this->render('housing/edit_meal.html.twig', [
            'meal' => $meal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Extra entity.
     *
     * @Route("/meal/{id<\d+>}/edit",methods={"GET", "POST"}, name="housing_meal_edit")
     *
     */
    public function editMeal(Request $request, Meal $meal, ObjectManager $em): Response
    {
        $form = $this->createForm(MealType::class, $meal)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'meal.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_meal_new');
            }

            return $this->redirectToRoute('housing_meal');
        }

        return $this->render('housing/edit_meal.html.twig', [
            'meal' => $meal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Meal entity.
     *
     * @Route("/meal/{id}/delete", methods={"GET", "POST"}, name="housing_meal_delete")
     *
     */
    public function deleteMeal(Request $request, Meal $meal, ObjectManager $em): Response
    {

        $em->remove($meal);
        $em->flush();

        $this->addFlash('success', 'meal.deleted_successfully');

        return $this->redirectToRoute('housing_meal');
    }

    /**
     * Lists all Season.
     *
     *
     * @Route("/season", methods={"GET"}, name="housing_season")
     *
     */
    public function showSeason(SeasonRepository $seasons): Response
    {
        $userSeasons = $seasons->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_season.html.twig', ['seasons' => $userSeasons]);
    }

    /**
     * Creates a new season entity.
     *
     * @Route("/season/new", methods={"GET", "POST"}, name="housing_season_new")
     *
     */
    public function newSeason(Request $request, Season $season = null, ObjectManager $em): Response
    {
        if(!$season){$season = new Season();}

        // On Instancie le formulaire
        $form = $this->createForm(SeasonType::class, $season)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $season->setCreatedAt(new\DateTime());
            $season->setUser($this->getUser());
            //$season->setIsEnabled(true);

            $em->persist($season);
            $em->flush();

            $this->addFlash('success', 'season .created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_season_new');
            }

            return $this->redirectToRoute('housing_season');
        }

        return $this->render('housing/edit_season.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Season entity.
     *
     * @Route("/season/{id<\d+>}/edit",methods={"GET", "POST"}, name="housing_season_edit")
     *
     */
    public function editSeason(Request $request, Season $season, ObjectManager $em): Response
    {
        $form = $this->createForm(SeasonType::class, $season)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'season.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_season_new');
            }

            return $this->redirectToRoute('housing_season');
        }

        return $this->render('housing/edit_season.html.twig', [
            'season' => $season,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Season entity.
     *
     * @Route("/season/{id}/delete", methods={"GET", "POST"}, name="housing_season_delete")
     *
     */
    public function deleteSeason(Request $request, Season $season, ObjectManager $em): Response
    {

        $em->remove($season);
        $em->flush();

        $this->addFlash('success', 'season.deleted_successfully');

        return $this->redirectToRoute('housing_season');
    }

    /**
     * Lists all Housing.
     *
     *
     * @Route("/housing", methods={"GET"}, name="housing_housing")
     * 
     */
    public function showHousing(HousingRepository $housings): Response
    {
        $userHousings = $housings->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_housing.html.twig', ['housings' => $userHousings]);
    }

    /**
     * Creates a new Housing entity.
     *
     * @Route("/housing/new", methods={"GET", "POST"}, name="housing_housing_new")
     *
     */
    public function newHousing(Request $request, Housing $housing = null, ObjectManager $em): Response
    {
        if(!$housing){$housing = new Housing();}

        // On Instancie le formulaire 
        $form = $this->createForm(HousingfType::class, $housing);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $housing->setCreatedAt(new\DateTime());
            $housing->setUser($this->getUser());
            $housing->setIsEnabled(true);

            $em->persist($housing);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'housing.created_successfully');

            return $this->redirectToRoute('housing_housing');
        }

        return $this->render('housing/edit_housing.html.twig', [
            'housing' => $housing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Housing entity.
     *
     * @Route("/housing/{id<\d+>}/edit",methods={"GET", "POST"}, name="housing_housing_edit")
     * 
     */
    public function editHousing(Request $request, Housing $housing, ObjectManager $em): Response
    {
        $form = $this->createForm(HousingfType::class, $housing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'housing.updated_successfully');

            return $this->redirectToRoute('housing_housing');
        }

        return $this->render('housing/edit_housing.html.twig', [
            'housing' => $housing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Housing entity.
     *
     * @Route("/housing/{id}/delete", methods={"GET", "POST"}, name="housing_housing_delete")
     * 
     */
    public function deleteHousing(Request $request, Housing $housing, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('setting_civility');
        }*/

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getTags()->clear();

        $em->remove($housing);
        $em->flush();

        $this->addFlash('success', 'housing.deleted_successfully');

        return $this->redirectToRoute('housing_housing');
    }

    /**
     * Lists all Tag Category.
     *
     *
     * @Route("/tag-category", methods={"GET"}, name="housing_tag-category")
     * 
     */
    public function showTagCategory(TagCategoryRepository $tagCategories): Response
    {
        $userTagCategories = $tagCategories->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_tag-category.html.twig', ['tagCategories' => $userTagCategories]);
    }

    /**
     * Creates a new Tag Category entity.
     *
     * @Route("/tag-category/new", methods={"GET", "POST"}, name="housing_tag-category_new")
     *
     */
    public function newTagCategory(Request $request, TagCategory $tagCategory = null, ObjectManager $em): Response
    {
        if(!$tagCategory){$tagCategory = new TagCategory();}

        // On Instancie le formulaire 
        $form = $this->createForm(TagCategoryType::class, $tagCategory)
        ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $tagCategory->setCreatedAt(new\DateTime());
            $tagCategory->setUser($this->getUser());
            //$tagCategory->setIsEnabled(true);

            $em->persist($tagCategory);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'tagcategory.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_tag-category_new');
            }

            return $this->redirectToRoute('housing_tag-category');
        }

        return $this->render('housing/edit_tag-category.html.twig', [
            'tagCategory' => $tagCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Tag Category entity.
     *
     * @Route("/tag-category/{id<\d+>}/edit",methods={"GET", "POST"}, name="housing_tag-category_edit")
     * 
     */
    public function editTagCategory(Request $request, TagCategory $tagCategory, ObjectManager $em): Response
    {
        $form = $this->createForm(TagCategoryType::class, $tagCategory)
        ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'tagcategory.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_tag-category_new');
            }

            return $this->redirectToRoute('housing_tag-category');
        }

        return $this->render('housing/edit_tag-category.html.twig', [
            'tagCategory' => $tagCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Tag Category entity.
     *
     * @Route("/tag-category/{id}/delete", methods={"GET", "POST"}, name="housing_tag-category_delete")
     * 
     */
    public function deleteTagCategory(Request $request, TagCategory $tagCategory, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('setting_civility');
        }*/

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getTags()->clear();

        $em->remove($tagCategory);
        $em->flush();

        $this->addFlash('success', 'tagcategory.deleted_successfully');

        return $this->redirectToRoute('housing_tag-category');
    }


    /**
     * Lists all Tag.
     *
     *
     * @Route("/tag", methods={"GET"}, name="housing_tag")
     * 
     */
    public function showTag(TagRepository $tags): Response
    {
        $userTags = $tags->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_tag.html.twig', ['tags' => $userTags]);
    }

    /**
     * Creates a new Tag entity.
     *
     * @Route("/tag/new", methods={"GET", "POST"}, name="housing_tag_new")
     *
     */
    public function newTag(Request $request, Tag $tag = null, ObjectManager $em): Response
    {
        if(!$tag){$tag = new Tag();}

        // On Instancie le formulaire 
        $form = $this->createForm(TagType::class, $tag)
        ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $tag->setCreatedAt(new\DateTime());
            $tag->setUser($this->getUser());
            //$tag->setIsEnabled(true);

            $em->persist($tag);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'tag.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_tag_new');
            }

            return $this->redirectToRoute('housing_tag');
        }

        return $this->render('housing/edit_tag.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Tag entity.
     *
     * @Route("/tag/{id<\d+>}/edit",methods={"GET", "POST"}, name="housing_tag_edit")
     * 
     */
    public function editTag(Request $request, Tag $tag, ObjectManager $em): Response
    {
        $form = $this->createForm(TagType::class, $tag)
        ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'tag.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('housing_tag_new');
            }

            return $this->redirectToRoute('housing_tag');
        }

        return $this->render('housing/edit_tag.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Tag entity.
     *
     * @Route("/tag/{id}/delete", methods={"GET", "POST"}, name="housing_tag_delete")
     * 
     */
    public function deleteTag(Request $request, Tag $tag, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('setting_civility');
        }*/

        // Delete the tags associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getTags()->clear();

        $em->remove($tag);
        $em->flush();

        $this->addFlash('success', 'tag.deleted_successfully');

        return $this->redirectToRoute('housing_tag');
    }


    /**
     * Lists all Equipment.
     *
     *
     * @Route("/equipment", methods={"GET"}, name="equipment")
     * 
     */
    public function showEquipment(EquipmentRepository $equipments): Response
    {
        $userEquipments = $equipments->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_equipment.html.twig', ['equipments' => $userEquipments]);
    }

    /**
     * Creates a new Equipment entity.
     *
     * @Route("/equipment/new", methods={"GET", "POST"}, name="equipment_new")
     *
     */
    public function newEquipment(Request $request, Equipment $equipment = null, ObjectManager $em): Response
    {
        if(!$equipment){$equipment = new Equipment();}

        // On Instancie le formulaire 
        $form = $this->createForm(EquipmentType::class, $equipment);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $equipment->setCreatedAt(new\DateTime());
            $equipment->setUser($this->getUser());
            $equipment->setIsEnabled(true);

            $em->persist($equipment);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'equipment.created_successfully');

            return $this->redirectToRoute('equipment');
        }

        return $this->render('housing/edit_equipment.html.twig', [
            'equipment' => $equipment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Equipment entity.
     *
     * @Route("/equipment/{id<\d+>}/edit",methods={"GET", "POST"}, name="equipment_edit")
     * 
     */
    public function editEquipment(Request $request, Equipment $equipment, ObjectManager $em): Response
    {
        $form = $this->createForm(EquipmentType::class, $equipment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'equipment.updated_successfully');

            return $this->redirectToRoute('equipment');
        }

        return $this->render('housing/edit_equipment.html.twig', [
            'equipment' => $equipment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Equipment entity.
     *
     * @Route("/equipment/{id}/delete", methods={"GET", "POST"}, name="equipment_delete")
     * 
     */
    public function deleteEquipment(Request $request, Equipment $equipment, ObjectManager $em): Response
    {
        // Delete the equipments associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getequipments()->clear();

        $em->remove($equipment);
        $em->flush();

        $this->addFlash('success', 'equipment.deleted_successfully');

        return $this->redirectToRoute('equipment');
    }

    /**
     * Lists all Equipment Categories.
     *
     *
     * @Route("/equipment-category", methods={"GET"}, name="equipment_category")
     * 
     */
    public function showEquipmentCategory(EquipmentCategoryRepository $equipmentCategories): Response
    {
        $userEquipmentCategories = $equipmentCategories->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_equipment-category.html.twig', ['equipmentCategories' => $userEquipmentCategories]);
    }

    /**
     * Creates a new Equipment Category entity.
     *
     * @Route("/equipment-category/new", methods={"GET", "POST"}, name="equipment_category_new")
     *
     */
    public function newEquipmentCategory(Request $request, EquipmentCategory $equipmentCategory = null, ObjectManager $em): Response
    {
        if(!$equipmentCategory){$equipmentCategory = new EquipmentCategory();}

        // On Instancie le formulaire 
        $form = $this->createForm(EquipmentCategoryType::class, $equipmentCategory);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $equipmentCategory->setCreatedAt(new\DateTime());
            $equipmentCategory->setUser($this->getUser());
            $equipmentCategory->setIsEnabled(true);

            $em->persist($equipmentCategory);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'equipmentcategory.created_successfully');

            return $this->redirectToRoute('equipment_category');
        }

        return $this->render('housing/edit_equipment-category.html.twig', [
            'equipmentCategory' => $equipmentCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Equipment Category entity.
     *
     * @Route("/equipment-category/{id<\d+>}/edit",methods={"GET", "POST"}, name="equipment_category_edit")
     * 
     */
    public function editEquipmentCategory(Request $request, EquipmentCategory $equipmentCategory, ObjectManager $em): Response
    {
        $form = $this->createForm(EquipmentCategoryType::class, $equipmentCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'equipmentcategory.updated_successfully');

            return $this->redirectToRoute('equipment_category');
        }

        return $this->render('housing/edit_equipment-category.html.twig', [
            'equipmentCategory' => $equipmentCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Equipment Category entity.
     *
     * @Route("/equipment-category/{id}/delete", methods={"GET", "POST"}, name="equipment_category_delete")
     * 
     */
    public function deleteEquipmentCategory(Request $request, EquipmentCategory $equipmentCategory, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('setting_civility');
        }*/

        // Delete the equipmentCategorys associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getequipmentCategorys()->clear();

        $em->remove($equipmentCategory);
        $em->flush();

        $this->addFlash('success', 'equipmentcategory.deleted_successfully');

        return $this->redirectToRoute('equipment_category');
    }

    /**
     * Lists all Extra.
     *
     *
     * @Route("/extra", methods={"GET"}, name="extra")
     * 
     */
    public function showExtra(ExtraRepository $extras): Response
    {
        $userExtras = $extras->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_extra.html.twig', ['extras' => $userExtras]);
    }

    /**
     * Creates a new Extra entity.
     *
     * @Route("/extra/new", methods={"GET", "POST"}, name="extra_new")
     *
     */
    public function newExtra(Request $request, Extra $extra = null, ObjectManager $em): Response
    {
        if(!$extra){$extra = new Extra();}

        // On Instancie le formulaire 
        $form = $this->createForm(ExtraType::class, $extra)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $extra->setCreatedAt(new\DateTime());
            $extra->setUser($this->getUser());
            $extra->setIsEnabled(true);

            $em->persist($extra);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'extra.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('extra_new');
            }


            return $this->redirectToRoute('extra');
        }

        return $this->render('housing/edit_extra.html.twig', [
            'extra' => $extra,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Extra entity.
     *
     * @Route("/extra/{id<\d+>}/edit",methods={"GET", "POST"}, name="extra_edit")
     * 
     */
    public function editExtra(Request $request, Extra $extra, ObjectManager $em): Response
    {
        $form = $this->createForm(ExtraType::class, $extra)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'extra.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('extra_new');
            }


            return $this->redirectToRoute('extra');
        }

        return $this->render('housing/edit_extra.html.twig', [
            'extra' => $extra,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Extra entity.
     *
     * @Route("/extra/{id}/delete", methods={"GET", "POST"}, name="extra_delete")
     * 
     */
    public function deleteExtra(Request $request, Extra $extra, ObjectManager $em): Response
    {
        // Delete the extras associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getextras()->clear();

        $em->remove($extra);
        $em->flush();

        $this->addFlash('success', 'extra.deleted_successfully');

        return $this->redirectToRoute('extra');
    }

    /**
     * Lists all Extra Categories.
     *
     *
     * @Route("/extra-category", methods={"GET"}, name="extra_category")
     * 
     */
    public function showExtraCategory(ExtraCategoryRepository $extraCategories): Response
    {
        $userEquipmentCategories = $extraCategories->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_extra-category.html.twig', ['extraCategories' => $userEquipmentCategories]);
    }

    /**
     * Creates a new Extra Category entity.
     *
     * @Route("/extra-category/new", methods={"GET", "POST"}, name="extra_category_new")
     *
     */
    public function newExtraCategory(Request $request, ExtraCategory $extraCategory = null, ObjectManager $em): Response
    {
        if(!$extraCategory){$extraCategory = new ExtraCategory();}

        // On Instancie le formulaire 
        $form = $this->createForm(ExtraCategoryType::class, $extraCategory)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/best_practices/forms.html#handling-form-submits
        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $extraCategory->setCreatedAt(new\DateTime());
            $extraCategory->setUser($this->getUser());
            //$extraCategory->setIsEnabled(true);

            $em->persist($extraCategory);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/book/controller.html#flash-messages
            $this->addFlash('success', 'extracategory.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('extra_category_new');
            }

            return $this->redirectToRoute('extra_category');
        }

        return $this->render('housing/edit_extra-category.html.twig', [
            'extraCategory' => $extraCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Extra Category entity.
     *
     * @Route("/extra-category/{id<\d+>}/edit",methods={"GET", "POST"}, name="extra_category_edit")
     * 
     */
    public function editExtraCategory(Request $request, ExtraCategory $extraCategory, ObjectManager $em): Response
    {
        $form = $this->createForm(ExtraCategoryType::class, $extraCategory)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'extracategory.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('extra_category_new');
            }

            return $this->redirectToRoute('extra_category');
        }

        return $this->render('housing/edit_extra-category.html.twig', [
            'extraCategory' => $extraCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Extra Category entity.
     *
     * @Route("/extra-category/{id}/delete", methods={"GET", "POST"}, name="extra_category_delete")
     * 
     */
    public function deleteExtraCategory(Request $request, ExtraCategory $extraCategory, ObjectManager $em): Response
    {
        /*if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
            return $this->redirectToRoute('setting_civility');
        }*/

        // Delete the extraCategorys associated with this blog post. This is done automatically
        // by Doctrine, except for SQLite (the database used in this application)
        // because foreign key support is not enabled by default in SQLite
        // $post->getextraCategorys()->clear();

        $em->remove($extraCategory);
        $em->flush();

        $this->addFlash('success', 'extracategory.deleted_successfully');

        return $this->redirectToRoute('extra_category');
    }

    /**
     * Lists all Pricing Plan.
     *
     *
     * @Route("/pricing-plan", methods={"GET"}, name="pricing_plan")
     * 
     */
    public function showPricingPlan(PricingPlanRepository $pricingplans): Response
    {
        $userPricingPlans = $pricingplans->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_pricingplan.html.twig', ['pricingplans' => $userPricingPlans]);
    }

    /**
     * Creates a new Pricing Plan entity.
     *
     * @Route("/pricing-plan/new", methods={"GET", "POST"}, name="pricing_plan_new")
     *
     */
    public function newPricingPlan(Request $request, PricingPlan $pricingplan = null, ObjectManager $em): Response
    {
        if(!$pricingplan){$pricingplan = new PricingPlan();}

        // On Instancie le formulaire 
        $form = $this->createForm(PricingPlanType::class, $pricingplan)
        ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $pricingplan->setCreatedAt(new\DateTime());
            $pricingplan->setUser($this->getUser());
            //$pricingplan->setIsEnabled(true);

            $em->persist($pricingplan);
            $em->flush();

            $this->addFlash('success', 'pricing_plan.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('pricing_plan_new');
            }

            return $this->redirectToRoute('pricing_plan');
        }

        return $this->render('housing/edit_pricingplan.html.twig', [
            'pricingplan' => $pricingplan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Pricing Plan entity.
     *
     * @Route("/pricing-plan/{id<\d+>}/edit",methods={"GET", "POST"}, name="pricing_plan_edit")
     * 
     */
    public function editPricingPlan(Request $request, PricingPlan $pricingplan, ObjectManager $em): Response
    {
        $form = $this->createForm(PricingPlanType::class, $pricingplan)
        ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'pricin_plan.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('pricing_plan_new');
            }

            return $this->redirectToRoute('pricing_plan');
        }

       return $this->render('housing/edit_pricingplan.html.twig', [
            'pricingplan' => $pricingplan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Pricing Plan entity.
     *
     * @Route("/pricing-plan/{id}/delete", methods={"GET", "POST"}, name="pricing_plan_delete")
     * 
     */
    public function PricingPlanDelete(Request $request, PricingPlan $pricingplan, ObjectManager $em): Response
    {

        $em->remove($pricingplan);
        $em->flush();

        $this->addFlash('success', 'pricing_plan.deleted_successfully');

        return $this->redirectToRoute('pricing_plan');
    }


    /**
     * Lists all Pricing.
     *
     *
     * @Route("/pricing", methods={"GET"}, name="pricing")
     * 
     */
    public function showPricings(PricingRepository $pricings): Response
    {
        $userPricings = $pricings->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_pricing.html.twig', ['pricings' => $userPricings]);
    }

    /**
     * Creates a new Pricing entity.
     *
     * @Route("/pricing/new", methods={"GET", "POST"}, name="pricing_new")
     *
     */
    public function newPricing(Request $request, Pricing $pricing = null, ObjectManager $em): Response
    {
        if(!$pricing){$pricing = new Pricing();}

        // On Instancie le formulaire 
        $form = $this->createForm(PricingType::class, $pricing)
        ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $pricing->setCreatedAt(new\DateTime());
            $pricing->setUser($this->getUser());
            //$pricing->setIsEnabled(true);

            $em->persist($pricing);
            $em->flush();

            $this->addFlash('success', 'pricing.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('pricing_new');
            }

            return $this->redirectToRoute('pricing');
        }

        return $this->render('housing/edit_pricing.html.twig', [
            'pricing' => $pricing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Pricing entity.
     *
     * @Route("/pricing/{id<\d+>}/edit",methods={"GET", "POST"}, name="pricing_edit")
     * 
     */
    public function editPricing(Request $request, Pricing $pricing, ObjectManager $em): Response
    {
        $form = $this->createForm(PricingType::class, $pricing)
        ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'pricing.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('pricing_new');
            }


            return $this->redirectToRoute('pricing');
        }

       return $this->render('housing/edit_pricing.html.twig', [
            'pricing' => $pricing,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Pricing entity.
     *
     * @Route("/pricing/{id}/delete", methods={"GET", "POST"}, name="pricing_delete")
     * 
     */
    public function deletePricing(Request $request, Pricing $pricing, ObjectManager $em): Response
    {

        $em->remove($pricing);
        $em->flush();

        $this->addFlash('success', 'pricing.deleted_successfully');

        return $this->redirectToRoute('pricing');
    }

    
    /**
     * Lists all Pricing.
     *
     *
     * @Route("/plan", methods={"GET"}, name="plan")
     * 
     */
    public function showTagPlan(TagPlanConfigurationRepository $plans): Response
    {
        $userPlans = $plans->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('housing/show_tagplan.html.twig', ['plans' => $userPlans]);
    }

    /**
     * Creates a new Pricing entity.
     *
     * @Route("/plan/new", methods={"GET", "POST"}, name="plan_new")
     *
     */
    public function newTagPlan(Request $request, TagPlanConfiguration $plan = null, ObjectManager $em): Response
    {
        if(!$plan){$plan = new TagPlanConfiguration();}

        // On Instancie le formulaire 
        $form = $this->createForm(TagPlanConfigType::class, $plan)
        ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister 
            $plan->setCreatedAt(new\DateTime());
            $plan->setUser($this->getUser());
            //$pricing->setIsEnabled(true);

            $em->persist($plan);
            $em->flush();

            $this->addFlash('success', 'plan.created_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('plan_new');
            }

            return $this->redirectToRoute('plan');
        }

        return $this->render('housing/edit_tagplan.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing Pricing entity.
     *
     * @Route("/plan/{id<\d+>}/edit",methods={"GET", "POST"}, name="plan_edit")
     * 
     */
    public function editPlan(Request $request, TagPlanConfiguration $plan, ObjectManager $em): Response
    {
        $form = $this->createForm(TagPlanConfigType::class, $plan)
        ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'plan.updated_successfully');
            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('plan_new');
            }


            return $this->redirectToRoute('plan');
        }

       return $this->render('housing/edit_tagplan.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Pricing entity.
     *
     * @Route("/plan/{id}/delete", methods={"GET", "POST"}, name="plan_delete")
     * 
     */
    public function deletePlan(Request $request, TagPlanConfiguration $plan, ObjectManager $em): Response
    {

        $em->remove($plan);
        $em->flush();

        $this->addFlash('success', 'plan.deleted_successfully');

        return $this->redirectToRoute('plan');
    }
}
