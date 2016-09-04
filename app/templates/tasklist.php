{% extends 'layout.twig' %}
{% block title %} Task{% endblock %}
{% block contant_wrapper %}
<div class="row">
    <div class="col-lg-12" style="margin: 20px">

    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                Task List
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th style="text-align: right">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% for tasks in task %}
                    <tr class="odd gradeX">
                        <td>{{ tasks.title }}</td>
                        <td>{{ tasks.description}}</td>
                        <td>{{ tasks.status}}</td>
                        <td style="text-align: right">
                            <a href="/details/{{ employee.id }}"><span class="glyphicon glyphicon-eye-open" data-toggle="tooltip" title="View"></span></a>
                            <a href="/update/{{ employee.id }}"><span class="glyphicon glyphicon-edit" data-toggle="tooltip" title="Edit" style="color: grey"></span></a>
                            <a href="/delete/{{ employee.id }}"><span class="glyphicon glyphicon-remove" data-toggle="tooltip" title="Remove" style="color: red"></span></a>
                        </td>
                    </tr>
                    {% endfor %}

                    </tbody>
                </table>

                {% endblock %}