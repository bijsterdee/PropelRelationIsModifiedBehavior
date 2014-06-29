<?php

class RelationIsModifiedBehavior extends Behavior
{
    public function objectAttributes(PHP5ObjectBuilder $builder)
    {
        return "
/**
 * @var bool
 */
protected \$alreadyInModified = false;
        ";
    }

    public function objectMethods(PHP5ObjectBuilder $builder)
    {
        $script = "
/**
 * In addition to checking if this object has been modified, it can also check if any of the related objects have been modified.
 *
 * @param bool \$checkRelations
 * @return bool
 */
public function isModified(\$checkRelations = false)
{
    if (!\$checkRelations) {
        return parent::isModified();
    }

    if (\$this->alreadyInModified) {
        return null;
    }

    \$this->alreadyInModified = true;

    \$modified = false;

    if (parent::isModified()) {
        \$modified = true;
    } else {";

        foreach ($builder->getTable()->getReferrers() as $refFK) {
            if ($refFK->isLocalPrimaryKey()) {
//                $varName = $builder->getPKRefFKVarName($refFK);
//                $script .= "
//    if (\$this->$varName !== null) {
//        if (!\$this->{$varName}->isDeleted() && (\$this->{$varName}->isNew() || \$this->{$varName}->isModified())) {
//            \$affectedRows += \$this->{$varName}->save(\$con);
//        }
//    }
//                ";
            } else {
//                $this->addRefFkScheduledForDeletion($script, $refFK);

                $collName = $builder->getRefFKCollVarName($refFK);
                $script .= "
        if (\$this->$collName !== null) {
            foreach (\$this->$collName as \$referrerFK) {
                if (\$referrerFK->isDeleted() || \$referrerFK->isNew() || \$referrerFK->isModified()) {
                    \$modified = true;
                    break;
                }
            }
        }";
            } // if refFK->isLocalPrimaryKey()

        } /* foreach getReferrers() */

        $script .= "
    }

    \$this->alreadyInModified = false;

    return \$modified;
}";

        return $script;
    }
}
