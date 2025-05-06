<?php

namespace App\Controller\Admin;

use App\Entity\EntireOrder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class EntireOrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntireOrder::class;
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Order')
            ->setEntityLabelInPlural('Orders')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    #[\Override]
    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::DETAIL)
            ->disable(Action::NEW);
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            AssociationField::new('user', 'Customer')
                ->setFormTypeOptions(['disabled' => true]),
            DateTimeField::new('createdAt', 'Created'),
            DateTimeField::new('startedAt', 'Started'),
            DateTimeField::new('completedAt', 'Completed'),
            NumberField::new('tax')
                ->setFormTypeOptions(['disabled' => true]),
            NumberField::new('totalPrice')
                ->setFormTypeOptions(['disabled' => true]),
            ChoiceField::new('status')
                ->setChoices([
                    'Placed' => 'placed',
                    'In Progess' => 'in_progress',
                    'Completed' => 'completed',
                ]),
        ];
    }
}
