<?php

namespace App\Controller\Admin;

use App\Entity\Menu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MenuCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Menu::class;
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            ChoiceField::new('category')
                ->setChoices([
                    'Sandwiches' => 'sandwiches',
                    'Sides' => 'sides',
                    'Beverages' => 'beverages',
                ]),
            NumberField::new('price'),
            TextField::new('description')->formatValue(fn ($value, $entity) => $value ?? 'N/A'),
            BooleanField::new('is_available'),
            TextField::new('image'),
        ];
    }

    #[\Override]
    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->showEntityActionsInlined()
        ->overrideTemplates([
            'crud/edit' => 'admin/menubackbutton.html.twig',
        ]);
    }
}
