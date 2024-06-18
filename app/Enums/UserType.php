<?php

namespace App;

enum UserType: string
{
    const ContentCreator = 'content-creator';
    const Travelpreneur = 'travelpreneur';
    const Admin = 'admin';
    const Store = 'store';
}