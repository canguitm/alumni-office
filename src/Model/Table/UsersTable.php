<?php
namespace App\Model\Table;

use Search\Manager;
use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\HasMany $AlumniProfiles
 * @property \Cake\ORM\Association\HasMany $CompanyDetails
 * @property \Cake\ORM\Association\HasMany $EducationalBackgrounds
 */
class UsersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('AlumniProfiles', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('CompanyDetails', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('EducationalBackgrounds', [
            'foreignKey' => 'user_id' 
        ]);

        $this->addBehavior('Search.Search');
        $this->searchManager()
            ->add('all', 'Search.Like', [
                'before' => true,
                'after' => true,
                'field' => [$this->aliasField('username')]
            ])

            ->add('foo', 'Search.Callback', [
                'callback' => function ($query, $args, $manager) {
                    // Modify $query as required
                }
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('username');

        $validator
            ->allowEmpty('password');

        $validator
            ->allowEmpty('role');

        $validator
            ->integer('active_status')
            ->allowEmpty('active_status');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['username']));
        return $rules;
    }
}
