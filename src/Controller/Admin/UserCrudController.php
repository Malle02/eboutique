<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Utilisateur')
            ->setEntityLabelInPlural('Utilisateurs')
            ->setDefaultSort(['name' => 'ASC'])
            ->setPageTitle('index', 'Liste des utilisateurs')
            ->setPageTitle('detail', fn (User $user) => sprintf('Détails de %s', $user->getName() ?: $user->getEmail()))
            ->setPageTitle('edit', fn (User $user) => sprintf('Modifier %s', $user->getName() ?: $user->getEmail()));
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom');
        yield EmailField::new('email', 'Email');
        yield TelephoneField::new('phone', 'Téléphone');
        yield TextField::new('address', 'Adresse');
        
        yield ChoiceField::new('roles', 'Rôles')
            ->setChoices([
                'Utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN'
            ])
            ->allowMultipleChoices()
            ->renderExpanded();

        if ($pageName === Crud::PAGE_DETAIL) {
            yield AssociationField::new('orders', 'Commandes');
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->setPermission(
                Action::DELETE, 
                "not is_granted('ROLE_ADMIN')"
            );
    }
}