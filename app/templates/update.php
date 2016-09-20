{% extends 'layout.twig' %}
{% block title %} Task Details{% endblock %}
{% block contant_wrapper %}
<div class="row">

    <div class="col-md-8 col-md-offset-2">
        <br><br>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Update Task</h3>
            </div>
            <div class="panel-body">
                <form class="" role="form" action="/task/insert" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label for="title">Task Title</label>
                                    <input type="text" name="title" value="{{ update_data.title }}" class="form-control" id="title"
                                           placeholder="Title">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="member">Select Member</label>
                                    <select class="form-control" name="member_id" id="member">
                                        <option value="{{update_data.member_id}}">{{update_data.membername}}</option>
                                        {% for members in member %}
                                        <option value="{{ members.id }}">{{ members.username }}</option>
                                        {% endfor %}

                                    </select>
                                </div>

                            </div>
                            <div class="form-group col-md-6">
                                <label for="status">Task Type</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="{{ update_data.task_type }}">
                                        {% if  update_data.task_type==1 %}
                                        Edit
                                        {% elseif update_data.task_type==2 %}
                                        Redesign
                                        {% elseif update_data.task_type==3 %}
                                        New
                                        {% endif %}
                                    </option>
                                    <option value="1" >Edit</option>
                                    <option value="2">Redesign</option>
                                    <option value="3">New</option>
                                </select>
                            </div>
                        </div>


                        <input class="" placeholder="" name="user_id" value="{{ session.user.0.id }}" type="hidden">


                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="client_id">Client ID</label>
                                <input type="text" name="client_id"  value="{{ update_data.client_id }}" class="form-control" id="client_id"
                                       placeholder="Input Client ID">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="date">Submission Date</label>
                                <input type="date" name="date" value="" class="form-control" id="date">
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="">Description</label>
                            <textarea name="description"  class="form-control" id="summernote">{{update_data.description}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="attached">Attached</label>
                            <input type="file" name="attached" class="form-control" id="attached" >
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>

    </div>
</div>
{% endblock %}