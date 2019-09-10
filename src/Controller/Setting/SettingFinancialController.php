<?php

namespace App\Controller\Setting;

use App\Entity\DatePeriod;
use App\Entity\PaymentMethod;
use App\Entity\Year;
use App\Entity\Tax;
use App\Entity\Currency;

use App\Form\DatePeriodType;
use App\Form\PaymentMethodType;
use App\Form\YearType;
use App\Form\TaxType;
use App\Form\CurrencyType;

use App\Repository\DatePeriodRepository;
use App\Repository\PaymentMethodRepository;
use App\Repository\YearRepository;
use App\Repository\TaxRepository;
use App\Repository\CurrencyRepository;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Monolog\Handler\LogEntriesHandler;
use Monolog\Handler\ZendMonitorHandler;
use Symfony\Bridge\Doctrine\Tests\Fixtures\GroupableEntity;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Firewall\AbstractAuthenticationListener;
use Twig\Node\Expression\Binary\ConcatBinary;
use Twig\Node\Expression\Binary\SubBinary;
use Twig\Node\Expression\MethodCallExpression;
use Twig\Profiler\Dumper\TextDumper;
use Twig_Sandbox_SecurityNotAllowedPropertyError;
use Twig_TokenParser_Macro;

/**
 * Controller used to manage Financial Settings.
 *
 * @author Hervé Marcel Jiogue Tadie <fastochehost@gmail.com>
 * @author My Team <>
 */
class SettingFinancialController extends AbstractController
{
    /**
     * Lists all Years .
     *
     *
     * @Route("{_locale}/year", methods={"GET"}, name="year")
     *
     */
    public function showYear(YearRepository $years): Response
    {
        $userYears = $years->findAll();

        return $this->render('setting_financial/show_year.html.twig', ['years' => $userYears]);
    }

    /**
     * Creates a new year   entity.
     *
     * @Route("{_locale}/year/new", methods={"GET", "POST"}, name="year_new")
     *
     */
    public function newYear(Request $request, Year $year = null, ObjectManager $em): Response
    {
        if(!$year){$year = new Year();}

        // On Instancie le formulaire
        $form = $this->createForm(YearType::class, $year)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $year->setCreatedAt(new\DateTime());
            $year->setUser($this->getUser());

            $em->persist($year);
            $em->flush();

            $this->addFlash('success', 'year.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('year_new');
            }

            return $this->redirectToRoute('year');
        }

        return $this->render('setting_financial/edit_year.html.twig', [
            'year' => $year,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing year entity.
     *
     * @Route("{_locale}/year/{id<\d+>}/edit",methods={"GET", "POST"}, name="year_edit")
     *
     */
    public function edityear(Request $request, Year $year, ObjectManager $em): Response
    {
        $form = $this->createForm(YearType::class, $year)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'year.updated_successfully');

            return $this->redirectToRoute('year');
        }

        return $this->render('setting_financial/edit_year.html.twig', [
            'year' => $year,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Year entity.
     *
     * @Route("/year/{id}/delete", methods={"GET", "POST"}, name="year_delete")
     *
     */
    public function deleteYear(Request $request, Year $year, ObjectManager $em): Response
    {
        $em->remove($year);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('year');
    }

    /**
     * Lists all Tax .
     *
     *
     * @Route("{_locale}/tax", methods={"GET"}, name="tax")
     *
     */
    public function showTax(TaxRepository $taxes): Response
    {
        $userTaxes = $taxes->findAll();

        return $this->render('setting_financial/show_tax.html.twig', ['taxes' => $userTaxes]);
    }

    /**
     * Creates a new tax   entity.
     *
     * @Route("{_locale}/tax/new", methods={"GET", "POST"}, name="tax_new")
     *
     */
    public function newTax(Request $request, Tax $tax = null, ObjectManager $em): Response
    {
        if(!$tax){$tax = new Tax();}

        // On Instancie le formulaire
        $form = $this->createForm(TaxType::class, $tax)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $tax->setCreatedAt(new\DateTime());
            $tax->setUser($this->getUser());

            $em->persist($tax);
            $em->flush();

            $this->addFlash('success', 'tax.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('tax_new');
            }

            return $this->redirectToRoute('tax');
        }

        return $this->render('setting_financial/edit_tax.html.twig', [
            'tax' => $tax,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing tax entity.
     *
     * @Route("{_locale}/tax/{id<\d+>}/edit",methods={"GET", "POST"}, name="tax_edit")
     *
     */
    public function editTax(Request $request, Tax $tax, ObjectManager $em): Response
    {
        $form = $this->createForm(TaxType::class, $tax)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'tax.updated_successfully');

            return $this->redirectToRoute('tax');
        }

        return $this->render('setting_financial/edit_tax.html.twig', [
            'tax' => $tax,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a tax entity.
     *
     * @Route("/tax/{id}/delete", methods={"GET", "POST"}, name="tax_delete")
     *
     */
    public function deleteTax(Tax $tax, ObjectManager $em): Response
    {
        $em->remove($tax);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('tax');
    }

    /**
     * Lists all Currency .
     *
     *
     * @Route("{_locale}/currency", methods={"GET"}, name="currency")
     *
     */
    public function showCurrency(CurrencyRepository $currencies): Response
    {
        $userCurrencies = $currencies->findAll();

        return $this->render('setting_financial/show_currency.html.twig', ['currencies' => $userCurrencies]);
    }

    /**
     * Creates a new Currency   entity.
     *
     * @Route("{_locale}/currency/new", methods={"GET", "POST"}, name="currency_new")
     *
     */
    public function newCurrency(Request $request, Currency $currency = null, ObjectManager $em): Response
    {
        if(!$currency){$currency = new Currency();}

        // On Instancie le formulaire
        $form = $this->createForm(CurrencyType::class, $currency)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();
            // On prépare l'objet à persister
            $currency->setCreatedAt(new\DateTime());
            $currency->setUser($this->getUser());
            //$log->setBranch($branch);
            //$season->setIsEnabled(true);

            $em->persist($currency);
            $em->flush();

            $this->addFlash('success', 'currency.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('currency_new');
            }

            return $this->redirectToRoute('currency');
        }

        return $this->render('setting_financial/edit_currency.html.twig', [
            'currency' => $currency,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing currency entity.
     *
     * @Route("{_locale}/currency/{id<\d+>}/edit",methods={"GET", "POST"}, name="currency_edit")
     *
     */
    public function editCurrency(Request $request, Currency $currency, ObjectManager $em): Response
    {
        $form = $this->createForm(CurrencyType::class, $currency)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'currency.updated_successfully');

            return $this->redirectToRoute('currency');
        }

        return $this->render('setting_financial/edit_currency.html.twig', [
            'currency' => $currency,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Currency entity.
     *
     * @Route("/currency/{id}/delete", methods={"GET", "POST"}, name="currency_delete")
     *
     */
    public function deleteCurrency(Request $request, Currency $currency, ObjectManager $em): Response
    {
        $em->remove($currency);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('currency');
    }

    /**
     * Lists all Payment Method .
     *
     *
     * @Route("{_locale}/payment-method", methods={"GET"}, name="payment_method")
     *
     */
    public function showPaymentMethod(PaymentMethodRepository $paymentMethods): Response
    {
        $userPaymentMethods = $paymentMethods->findAll();

        return $this->render('setting_financial/show_paymentmethod.html.twig', ['paymentMethods' => $userPaymentMethods]);
    }

    /**
     * Creates a new PaymentMethod   entity.
     *
     * @Route("{_locale}/payment-method/new", methods={"GET", "POST"}, name="payment_method_new")
     *
     */
    public function newPaymentMethod(Request $request, PaymentMethod $paymentMethod = null, ObjectManager $em): Response
    {
        if(!$paymentMethod){$paymentMethod = new PaymentMethod();}

        // On Instancie le formulaire
        $form = $this->createForm(PaymentMethodType::class, $paymentMethod)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$branch = $user->findOneBy(['id' => $this->getUser()])->getBranch();
            // On prépare l'objet à persister
            $paymentMethod->setCreatedAt(new\DateTime());
            $paymentMethod->setUser($this->getUser());
            //$log->setBranch($branch);
            //$season->setIsEnabled(true);

            $em->persist($paymentMethod);
            $em->flush();

            $this->addFlash('success', 'payment method.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('payment_method_new');
            }

            return $this->redirectToRoute('payment_method');
        }

        return $this->render('setting_financial/edit_paymentmethod.html.twig', [
            'paymentMethod' => $paymentMethod,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing payment Method entity.
     *
     * @Route("{_locale}/payment-method/{id<\d+>}/edit",methods={"GET", "POST"}, name="payment_method_edit")
     *
     */
    public function editPaymentMethod(Request $request, PaymentMethod $paymentMethod, ObjectManager $em): Response
    {
        $form = $this->createForm(PaymentMethodType::class, $paymentMethod)
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'payment.method.updated_successfully');

            return $this->redirectToRoute('payment_method');
        }

        return $this->render('setting_financial/edit_paymentmethod.html.twig', [
            'paymentMethod' => $paymentMethod,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a payment method entity.
     *
     * @Route("/payment-method/{id}/delete", methods={"GET", "POST"}, name="payment_method_delete")
     *
     */
    public function PaymentmethodDelete(Request $request, PaymentMethod $paymentMethod, ObjectManager $em): Response
    {

        $em->remove($paymentMethod);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('payment_method');
    }

    /**
     * Lists all Years .
     *
     *
     * @Route("{_locale}/period", methods={"GET"}, name="period")
     *
     */
    public function showDatePeriod(DatePeriodRepository $periods): Response
    {
        $userPeriods = $periods->findAll();

        return $this->render('setting_financial/show_period.html.twig', ['periods' => $userPeriods]);
    }

    /**
     * Creates a new year   entity.
     *
     * @Route("{_locale}/period/new", methods={"GET", "POST"}, name="period_new")
     *
     */
    public function newDatePeriod(Request $request, DatePeriod $period = null, ObjectManager $em): Response
    {
        if(!$period){$period = new DatePeriod();}

        // On Instancie le formulaire
        $form = $this->createForm(DatePeriodType::class, $period)
            ->add('dateTo', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
                'data' => new \DateTime("now"),
            ))
            ->add('dateFrom', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
                'data' => new \DateTime("now"),
            ))
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On prépare l'objet à persister
            $period->setCreatedAt(new\DateTime());
            $period->setUser($this->getUser());

            $em->persist($period);
            $em->flush();

            $this->addFlash('success', 'year.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('period_new');
            }

            return $this->redirectToRoute('period');
        }

        return $this->render('setting_financial/edit_period.html.twig', [
            'period' => $period,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Displays a form to edit an existing year entity.
     *
     * @Route("{_locale}/period/{id<\d+>}/edit",methods={"GET", "POST"}, name="period_edit")
     *
     */
    public function editDatePeriod(Request $request, DatePeriod $period, ObjectManager $em): Response
    {
        $form = $this->createForm(DatePeriodType::class, $period)
            ->add('dateTo', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
            ->add('dateFrom', DateType::class, array(
                'widget' => 'single_text',
                'attr' => array(
                    'class' => 'form-control',
                    'id' => 'anytime-month-numeric',
                ),
                'required' => true,
            ))
            ->add('saveAndCreateNew', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'year.updated_successfully');

            return $this->redirectToRoute('period');
        }

        return $this->render('setting_financial/edit_period.html.twig', [
            'period' => $period,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a Year entity.
     *
     * @Route("/period/{id}/delete", methods={"GET", "POST"}, name="period_delete")
     *
     */
    public function deleteDatePeriod(DatePeriod $period, ObjectManager $em): Response
    {
        $em->remove($period);
        $em->flush();

        $this->addFlash('success', 'Success!');
        return $this->redirectToRoute('period');
    }

}