<?= $helper->getHeadPrintCode($entity_class_name.' index'); ?>

{% block body %}
    <div class="container-fluid mt-3">
        <h1><?= $entity_class_name ?>s</h1>

        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between">
                <a class="btn btn-primary" href="{{ path('<?= $route_name ?>_new') }}">New</a>
            </div>
        </div>

        <div class="row mb-3">
            <table class="table">
                <thead>
                    <tr>
<?php foreach ($entity_fields as $col => $field): ?>
                        <th><?= ucfirst($field['fieldName']) ?></th>
<?php endforeach; ?>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
                    <tr>
<?php foreach ($entity_fields as $col => $field): ?>

<?php if ($col === 0): ?>
        <td>
            <a href="{{ path('<?= $route_name ?>_show', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}">
                {{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}
            </a>
        </td>
    <?php else: ?>
        <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
<?php endif; ?>

<?php endforeach; ?>
                        <td>
                            <a href="{{ path('<?= $route_name ?>_edit', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}" class="btn btn-outline-danger">
                                <i class="fas fa-edit" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                {% else %}
                    <tr>
                        <td class="text-center" colspan="<?= (count($entity_fields) + 1) ?>">
                            no records found
                        </td>
                    </tr>
                {% endfor %}
                </tbody>
            </table>
        </div>
    </div>
{% endblock %}
