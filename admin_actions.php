<?php
enum Action: string {
    case AddStudent = "add_student";
    case DeleteStudent = "delete_student";
    case UpdateStudent = "update_student";
    case AddTeacher = "add_teacher";
    case AddClass = "add_class";
    case AssignClassTeacher = "assign_class_teacher";
}
