<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    #[\Override]
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username'),
            ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->renderExpanded()
                ->setChoices([
                    'User' => 'ROLE_USER',
                    'Employee' => 'ROLE_EMPLOYEE',
                    'Manager' => 'ROLE_MANAGER',
                    'Admin' => 'ROLE_ADMIN',
                ]),
            TextField::new('plainPassword')
                ->hideOnIndex()
                ->setFormType(RepeatedType::class)
                ->setFormTypeOptions([
                    'type' => PasswordType::class,
                    'invalid_message' => 'The password fields must match.',
                    'first_options' => ['label' => 'Password', 'attr' => ['style' => 'width: 32ch']],
                    'second_options' => ['label' => 'Repeat Password', 'attr' => ['style' => 'width: 32ch']],
                    'required' => false,
                ]),
            TextField::new('email')
                ->hideOnIndex()
                ->hideOnDetail(),
            TextField::new('firstName'),
            TextField::new('lastName'),
            TextField::new('phone')
                ->hideOnIndex(),
            TextField::new('street')
                ->hideOnIndex(),
            TextField::new('city')
                ->hideOnIndex(),
            TextField::new('state')
                ->hideOnIndex(),
            TextField::new('zip')
                ->hideOnIndex(),
        ];
    }

    #[\Override]
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encryptPassword($entityInstance);
        parent::persistEntity($entityManager, $entityInstance);
    }

    #[\Override]
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->encryptPassword($entityInstance);
        parent::updateEntity($entityManager, $entityInstance);
    }

    private function encryptPassword(User $entityInstance): void
    {
        // Hash and set new password if provided.
        // Using an event handler would have been less redundant.
        if ($plainPassword = $entityInstance->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword($entityInstance, $plainPassword);
            $entityInstance->setPassword($hashedPassword);
        }
    }
}
