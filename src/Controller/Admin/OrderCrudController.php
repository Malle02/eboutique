<?php

// namespace App\Controller\Admin;

// use App\Entity\Order;
// use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
// use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
// use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

// class OrderCrudController extends AbstractCrudController
// {
//     public static function getEntityFqcn(): string
//     {
//         return Order::class;
//     }

//     public function configureFields(string $pageName): iterable
//     {
//         return [
//             TextField::new('orderNumber', 'Numéro de commande'),
//             DateTimeField::new('date', 'Date de commande'),
//             TextField::new('status', 'Statut'),
//             AssociationField::new('user', 'Client'),
//         ];
//     }
// }






namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\OrderItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Commande')
            ->setEntityLabelInPlural('Commandes')
            ->setDefaultSort(['date' => 'DESC'])
            ->setPageTitle('index', 'Gestion des commandes')
            ->setPageTitle('detail', fn (Order $order) => sprintf('Commande #%s', $order->getOrderNumber()))
            ->setPageTitle('edit', fn (Order $order) => sprintf('Modifier la commande #%s', $order->getOrderNumber()));
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id')->hideOnForm(),
            TextField::new('orderNumber', 'Numéro de commande')
                ->setFormTypeOption('disabled', true),
            DateTimeField::new('date', 'Date de commande')
                ->setFormTypeOption('disabled', true),
            ChoiceField::new('status', 'Statut')
                ->setChoices([
                    'En attente' => 'En attente',
                    'En préparation' => 'En préparation',
                    'Expédiée' => 'Expédiée',
                    'Livrée' => 'Livrée',
                    'Annulée' => 'Annulée'
                ]),
            AssociationField::new('user', 'Client')
                ->setFormTypeOption('disabled', true),
            MoneyField::new('total', 'Total')
                ->setCurrency('EUR')
                ->setStoredAsCents(true)
                ->setFormTypeOption('disabled', true),
        ];

        // Afficher les détails de commande uniquement sur la page de détail
        if ($pageName === Crud::PAGE_DETAIL) {
            $fields[] = CollectionField::new('orderItems', 'Produits commandés')
                ->setTemplatePath('admin/order_items.html.twig'); // Template personnalisé pour afficher les items
        }

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        $viewInvoice = Action::new('viewInvoice', 'Facture', 'fa fa-file-invoice')
            ->linkToCrudAction('generateInvoice');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_DETAIL, $viewInvoice)
            ->disable(Action::NEW);
    }

    public function generateInvoice(AdminUrlGenerator $adminUrlGenerator): Response
    {
        // Logique pour générer une facture PDF
        // ...

        // Pour l'instant, on redirige vers la page de détail
        $entityId = $this->getContext()->getRequest()->query->get('entityId');
        
        return $this->redirect(
            $adminUrlGenerator
                ->setController(self::class)
                ->setAction(Action::DETAIL)
                ->setEntityId($entityId)
                ->generateUrl()
        );
    }
}