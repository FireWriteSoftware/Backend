<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    public $permission = ["roles_get_all", "users_get_all", "users_update", "users_get_single", "users_reset_password", "users_verify_email", "users_change_password", "users_delete", "bans_get_all", "bans_store", "bans_get_single", "bans_update", "bans_delete", "user_bans_get_all", "user_bans_store", "user_bans_get_single", "user_bans_delete", "user_badges_store_array", "badges_get_all", "user_badges_delete", "user_badges_get_all", "user_badges_delete_array", "posts_update", "posts_delete", "posts_history_get_all", "posts_history_get_post", "posts_history_get_single", "posts_get_all", "posts_get_single", "posts_store", "posts_comments_get_all", "posts_comments_get_post", "posts_comments_get_single", "posts_comments_store", "posts_comments_update", "posts_comments_delete", "bans_count", "user_bans_count", "roles_get_single", "posts_recent", "announcements_get_all", "announcements_store", "announcements_update", "announcements_delete", "announcements_get_single", "categories_get_all", "permissions_get_all", "permissions_test", "environment_update_mysql", "permissions_store", "permissions_delete", "permissions_update", "roles_permissions_get_all", "roles_permissions_delete", "roles_permissions_store", "roles_update", "roles_delete", "roles_store", "environment_update_mail", "permissions_get_single", "tags_get_all", "tags_store", "tags_update", "tags_delete", "tags_get_single", "posts_reports_get_all", "posts_reports_get_post", "posts_reports_get_single", "posts_report_store", "badges_store", "badges_update", "badges_delete", "posts_report_update", "posts_report_delete", "notifications_get_all", "notifications_get_user", "notifications_get_single", "notifications_create", "notifications_update", "notifications_destroy", "categories_update", "notifications_get_recent", "announcements_force_delete", "announcements_recover", "notifications_force_delete", "notifications_recover", "notifications_delete", "posts_recover", "posts_history_get_posts", "posts_history_delete", "posts_history_force_delete", "posts_history_recover", "bans_force_delete", "bans_recover", "categories_get_structured", "permissions_force_delete", "permissions_recover", "roles_force_delete", "roles_recover", "roles_permissions_check", "roles_permissions_attach", "roles_permissions_detach", "users_force_delete", "users_recover", "categories_force_delete", "categories_recover", "categories_get_single", "users_badges_get_all", "users_badges_check", "users_badges_attach", "users_badges_detach", "categories_store", "categories_delete", "posts_votes_get_all", "posts_votes_get_post", "posts_votes_get_single", "posts_votes_store", "bookmarks_get_all", "bookmarks_get_single", "bookmarks_store", "bookmarks_update", "bookmarks_delete", "bookmarks_force_delete", "bookmarks_recover", "posts_bookmarks_get_posts", "categories_bookmarks_get_posts", "users_bookmarks_get_all", "posts_approve", "users_get_posts", "posts_tags_check", "posts_tags_attach", "posts_permissions_detach", "badges_get_single", "tags_destroy", "test_permission_test", "posts_get_unauthorized", "notifications_get_own", "category_author", "category_moderation", "category_administration", "category_default", "users_bans_unban", "permissions_get_active", "posts_view_unapproved", "roles_permissions_get_users"];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_role = Role::where('name', 'Administrator')->first();
        $first_user = User::first();

        foreach ($this->permission as $permission) {
            $permission = Permission::create([
                'name' => $permission,
                'user_id' => $first_user->id
            ]);

            $admin_role->relations()->save($permission);
        }
    }
}
