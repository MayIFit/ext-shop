<?php

namespace MayIFit\Core\Translation\Database\Seeds;

use Illuminate\Database\Seeder;
use MayIFit\Core\Translation\Models\Translation;

/**
 * Class TranslationsTableSeeder
 *
 * @package MayIFit\Core\Translation
 */
class TranslationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (Translation::count() == 0) {
            $this->addGeneralTranslations();
            $this->addUserAttributeTranslations();
            $this->addDateRelatedTranslations();
            $this->addEntityEventTranslations();
            $this->addStateTranslations();
            $this->actionTranslations();
            $this->errorTranslations();
        }
    }

    protected function addGeneralTranslations() {
        Translation::create([
            'group' => 'global',
            'key' => 'name',
            'text' => ['en' => 'Name', 'hu' => 'Név'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'type',
            'text' => ['en' => 'Type', 'hu' => 'Típus'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'translations',
            'text' => ['en' => 'Translations', 'hu' => 'Fordítások'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'users',
            'text' => ['en' => 'Users', 'hu' => 'Felhasználók'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'description',
            'text' => ['en' => 'Description', 'hu' => 'Leírás'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'permissions',
            'text' => ['en' => 'Permissions', 'hu' => 'Jogosultságok'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'roles',
            'text' => ['en' => 'Roles', 'hu' => 'Szerepkörök'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'information',
            'text' => ['en' => 'Information', 'hu' => 'Információ'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'email',
            'text' => ['en' => 'Email', 'hu' => 'E-mail'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'password',
            'text' => ['en' => 'Password', 'hu' => 'Jelszó'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'key',
            'text' => ['en' => 'Key', 'hu' => 'Kulcs'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'group',
            'text' => ['en' => 'Group', 'hu' => 'Csoport'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'language',
            'text' => ['en' => 'Language', 'hu' => 'Nyelv'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'value',
            'text' => ['en' => 'Value', 'hu' => 'Érték'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'filters',
            'text' => ['en' => 'Filters', 'hu' => 'Szűrők'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'system',
            'text' => ['en' => 'System', 'hu' => 'Rendszer'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'settings',
            'text' => ['en' => 'Settings', 'hu' => 'Beállítások'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'breadcrumbs',
            'text' => ['en' => 'Breadcrumbs', 'hu' => 'Morzsák'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'has_right',
            'text' => ['en' => 'Has Right', 'hu' => 'Jogosult'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'list_view',
            'text' => ['en' => 'List View', 'hu' => 'Lista Nézet'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'board_view',
            'text' => ['en' => 'Board View', 'hu' => 'Tábla Nézet'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'system_settings',
            'text' => ['en' => 'System Settings', 'hu' => 'Rendszer Beállítások'],
        ]);
        Translation::create([
            'group' => 'global',
            'key' => 'entity',
            'text' => ['en' => 'Entity', 'hu' => 'Elem'],
        ]);
    }

    protected function addUserAttributeTranslations() {
        Translation::create([
            'group' => 'user',
            'key' => 'user',
            'text' => ['en' => 'User', 'hu' => 'Felhasználó'],
        ]);
        Translation::create([
            'group' => 'user',
            'key' => 'profile',
            'text' => ['en' => 'User profile', 'hu' => 'Felhasználói profil'],
        ]);
        Translation::create([
            'group' => 'user',
            'key' => 'real_name',
            'text' => ['en' => 'Real Name', 'hu' => 'Teljes, Valós Név'],
        ]);
        Translation::create([
            'group' => 'user',
            'key' => 'name',
            'text' => ['en' => 'User Name', 'hu' => 'Felhasználónév'],
        ]);
    }

    protected function addDateRelatedTranslations() {
        Translation::create([
            'group' => 'date',
            'key' => 'date',
            'text' => ['en' => 'Date', 'hu' => 'Dátum'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'created_at',
            'text' => ['en' => 'Created at', 'hu' => 'Létrehozva'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'updated_at',
            'text' => ['en' => 'Updated at', 'hu' => 'Módosítva'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'deleted_at',
            'text' => ['en' => 'Deleted at', 'hu' => 'Törölve'],
        ]);            
        Translation::create([
            'group' => 'date',
            'key' => 'permitted_at',
            'text' => ['en' => 'Permitted at', 'hu' => 'Jóváhagyva'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'this_month',
            'text' => ['en' => 'This month', 'hu' => 'Ez a hónap'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'prev_month',
            'text' => ['en' => 'Previous month', 'hu' => 'Előző hónap'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'from',
            'text' => ['en' => 'Date From', 'hu' => 'Dátum-tól'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'to',
            'text' => ['en' => 'Date To', 'hu' => 'Dátum-ig'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'workday',
            'text' => ['en' => 'Workday', 'hu' => 'Munkanap'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'non_working_day',
            'text' => ['en' => 'Non working day', 'hu' => 'Munkaszüneti nap'],
        ]);
        Translation::create([
            'group' => 'date',
            'key' => 'now',
            'text' => ['en' => 'Now', 'hu' => 'Most'],
        ]);
    }

    protected function addEntityEventTranslations() {
        Translation::create([
            'group' => 'entity',
            'key' => 'created',
            'text' => ['en' => 'Entity created', 'hu' => 'Elem létrehozva'],
        ]);
        Translation::create([
            'group' => 'entity',
            'key' => 'deleted',
            'text' => ['en' => 'Entity deleted', 'hu' => 'Elem törölve'],
        ]);
        Translation::create([
            'group' => 'entity',
            'key' => 'updated',
            'text' => ['en' => 'Entity updated', 'hu' => 'Elem módosítva'],
        ]);
        Translation::create([
            'group' => 'entity',
            'key' => 'exists',
            'text' => ['en' => 'Entity already exists', 'hu' => 'Elem már létezik'],
        ]);
        Translation::create([
            'group' => 'entity',
            'key' => 'uploaded',
            'text' => ['en' => 'Entity uploaded', 'hu' => 'Elem feltöltve'],
        ]);
        Translation::create([
            'group' => 'entity',
            'key' => 'created_by',
            'text' => ['en' => 'Created by', 'hu' => 'Létrehozta'],
        ]);
        Translation::create([
            'group' => 'entity',
            'key' => 'updated_by',
            'text' => ['en' => 'Updated by', 'hu' => 'Módosította'],
        ]);
        Translation::create([
            'group' => 'entity',
            'key' => 'deleted_by',
            'text' => ['en' => 'Deleted by', 'hu' => 'Törölte'],
        ]);
    }

    protected function addStateTranslations() {
        Translation::create([
            'group' => 'state',
            'key' => 'state',
            'text' => ['en' => 'state', 'hu' => 'Státusz'],
        ]);
        Translation::create([
            'group' => 'state',
            'key' => 'pending',
            'text' => ['en' => 'Pending', 'hu' => 'Elfogadásra vár'],
        ]);
        Translation::create([
            'group' => 'state',
            'key' => 'permitted',
            'text' => ['en' => 'Permitted', 'hu' => 'Engedélyezve'],
        ]);
        Translation::create([
            'group' => 'state',
            'key' => 'active',
            'text' => ['en' => 'Active', 'hu' => 'Aktív'],
        ]);
        Translation::create([
            'group' => 'state',
            'key' => 'inactive',
            'text' => ['en' => 'Inactive', 'hu' => 'Inaktív'],
        ]);
        Translation::create([
            'group' => 'state',
            'key' => 'disabled',
            'text' => ['en' => 'Disabled', 'hu' => 'Kikapcsolva'],
        ]);
        Translation::create([
            'group' => 'state',
            'key' => 'enabled',
            'text' => ['en' => 'Enabled', 'hu' => 'Bekapcsolva'],
        ]);
    }

    protected function actionTranslations() {
        Translation::create([
            'group' => 'action',
            'key' => 'actions',
            'text' => ['en' => 'Actions', 'hu' => 'Kezelés'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'search',
            'text' => ['en' => 'Search', 'hu' => 'Keresés'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'print',
            'text' => ['en' => 'Print', 'hu' => 'Nyomtatás'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'check_all',
            'text' => ['en' => 'Check All', 'hu' => 'Összes Kijelölése'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'reset_filter',
            'text' => ['en' => 'Reset Filter', 'hu' => 'Szűrők Törlése'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'show_more',
            'text' => ['en' => 'Show More', 'hu' => 'Mutass Többet'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'confirm_delete',
            'text' => ['en' => 'Confirm delete', 'hu' => 'Törlés megerősítése'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'save',
            'text' => ['en' => 'Save', 'hu' => 'Mentés'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'cancel',
            'text' => ['en' => 'Cancel', 'hu' => 'Mégse'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'back',
            'text' => ['en' => 'Back', 'hu' => 'Vissza'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'next',
            'text' => ['en' => 'Next', 'hu' => 'Következő'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'previous',
            'text' => ['en' => 'Previous', 'hu' => 'Előző'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'add_new',
            'text' => ['en' => 'Add new', 'hu' => 'Új érték hozzáadása'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'create',
            'text' => ['en' => 'Create new', 'hu' => 'Új létrehozása'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'delete',
            'text' => ['en' => 'Delete', 'hu' => 'Törlés'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'edit',
            'text' => ['en' => 'Edit', 'hu' => 'Szerkesztés'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'view',
            'text' => ['en' => 'View', 'hu' => 'Megtekintés'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'list',
            'text' => ['en' => 'List', 'hu' => 'Listázás'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'logout',
            'text' => ['en' => 'Logout', 'hu' => 'Kijelentkezés'],
        ]);
        Translation::create([
            'group' => 'action',
            'key' => 'login',
            'text' => ['en' => 'Login', 'hu' => 'Bejelentkezés'],
        ]);
    }

    protected function errorTranslations() {
        Translation::create([
            'group' => 'error',
            'key' => 'error',
            'text' => ['en' => 'Error', 'hu' => 'Hiba'],
        ]);
        Translation::create([
            'group' => 'error',
            'key' => 'module_disabled',
            'text' => ['en' => 'Module is disabled!', 'hu' => 'Modul nincs aktiválva!'],
        ]);
        Translation::create([
            'group' => 'error',
            'key' => 'unauthorized',
            'text' => ['en' => 'Unauthorized action!', 'hu' => 'Jogosulatlan művelet!'],
        ]);
    }
}
