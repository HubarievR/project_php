<?php
/**
 * Article type.
 */

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Article;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArticleType.
 */
class ArticleType extends AbstractType
{
    /**
     * Tags data transformer.
     */
    private TagsDataTransformer $tagsDataTransformer;

    /**
     * Constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TagsDataTransformer $tagsDataTransformer)
    {
        $this->tagsDataTransformer = $tagsDataTransformer;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
//        $builder->add(
//            'author',
//            TextType::class,
//            options: [
//                'label' => 'label.author',
//                'required' => true,
//                'attr' => ['max_length' => 255],
//            ]
//        );

        $builder->add(
            'title',
            TextType::class,
            options: [
                'label' => 'label.title',
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );

        $builder->add(
            'category',
            EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => function ($category): string {
                    return $category->getTitle();
                },
                'label' => 'label.category',
                'placeholder' => 'label.none',
                'required' => true,
            ]
        );

        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label.tags',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );

        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );

        $builder->add(
            'news',
            TextType::class,
            [
                'label' => 'label.news',
                'required' => true,
                'attr' => ['max_length' => 1000],
            ]
        );
    }

    /**
     * Configure Options.
     *
     * @param OptionsResolver $resolver ConfigureOptions
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Article::class]);
    }

    /**
     * Getter for BlockPrefix.
     *
     * @return string BlockPrefix
     */
    public function getBlockPrefix(): string
    {
        return 'article';
    }
}
