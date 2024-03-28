create table admins
(
    id       int primary key auto_increment,
    username varchar(255),
    password varchar(255)
);

create table teachers
(
    id   int primary key auto_increment,
    name varchar(255)
);

create table classes
(
    id         int primary key auto_increment,
    class      int,
    teacher_id int references teachers (id)
);

create table students
(
    id          int primary key auto_increment,
    name        varchar(255),
    middle_name varchar(255),
    dob         date,
    class_id    int references classes (id)
);

create table audit
(
    id        int primary key auto_increment,
    admin_id  int references admins (id),
    action    varchar(255),
    timestamp timestamp
);

create table audit_details
(
    id           int primary key auto_increment,
    old_value    varchar(255),
    new_value    varchar(255),
    audit_log_id int references audit (id)
);



