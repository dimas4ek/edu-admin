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
    teacher_id int,
    foreign key (teacher_id) references teachers (id)
);

create table students
(
    id          int primary key auto_increment,
    first_name        varchar(255),
    middle_name varchar(255),
    last_name varchar(255),
    dob         date,
    class_id    int,
    foreign key (class_id) references classes (id) on delete cascade
);

create table audit
(
    id        int primary key auto_increment,
    admin_id  int,
    action    varchar(255),
    timestamp timestamp,
    foreign key (admin_id) references admins (id)
);

create table audit_details
(
    id           int primary key auto_increment,
    old_value    varchar(255),
    new_value    varchar(255),
    audit_log_id int,
    foreign key (audit_log_id) references audit (id) on delete cascade
);



