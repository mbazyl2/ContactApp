<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add("street", null , ["label"=>"Street: ",])
                ->add("number", null, ["label"=>"Number: "])
                ->add("city", null, ["label"=>"City: "]);

    }
    // metoda powyzej ma za zadanie zbudowanie formularza o polach podanych

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Address'
        ));
    }
    // configureOptions mowi tylko o tym ze operujemy formularzem na obiektach AppBundle\Entity\Address

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_address_type';
    }
    // pozwala dac kazdem formularzowi inny prefix gdy tworzymy wiele takich samych formularzy do roznych celow

}
