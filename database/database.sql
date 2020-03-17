create table provinces
(
    id   bigint auto_increment
        primary key,
    name varchar(50) not null,
    constraint province_name_uindex
        unique (name)
);

create table districts
(
    id          bigint auto_increment
        primary key,
    name        varchar(50) not null,
    province_id bigint      not null,
    constraint district_name_uindex
        unique (name),
    constraint district_province_id_fk
        foreign key (province_id) references provinces (id)
);

create table users
(
    id            bigint auto_increment
        primary key,
    name          varchar(100)                       not null,
    email         varchar(100)                       not null,
    phone         varchar(15)                        not null,
    password      varchar(100)                       not null,
    shop          varchar(255)                       not null,
    referral_code varchar(50)                        null,
    created_at    datetime default CURRENT_TIMESTAMP null,
    updated_at    datetime default CURRENT_TIMESTAMP null,
    constraint users_email_uindex
        unique (email),
    constraint users_phone_uindex
        unique (phone)
);

create table wards
(
    id          bigint auto_increment
        primary key,
    name        varchar(50) not null,
    district_id bigint      not null,
    constraint ward_name_uindex
        unique (name),
    constraint ward_district_id_fk
        foreign key (district_id) references districts (id)
);

create table repositories
(
    id       bigint auto_increment
        primary key,
    phone    varchar(15)  not null,
    contact  varchar(100) not null,
    address  varchar(50)  not null,
    ward_id  bigint       not null,
    owner_id bigint       not null,
    constraint repositories_users_id_fk
        foreign key (owner_id) references users (id),
    constraint repository_ward_id_fk
        foreign key (ward_id) references wards (id)
);


