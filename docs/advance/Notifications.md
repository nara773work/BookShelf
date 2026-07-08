Notifications
//概要
通知一覧を表示、既読操作ができる

app/Enums/ReadingPlanStatus
//概要
ステータスを設定する
読書中、読了、期限切れ（期日が今日を過ぎたら期限切れになる）

//マイグレーション
uuid('id')->primary()
string('type');
morphs('notifiable');
json('data');
timestamp('read_at')->nullable();
timestamps();

app/notifications/ExiredNotifications,ReminderNotifications
//概要
通知の中身を設定する

期日切れ：期日が今日を過ぎると通知される　『タイトル』の期日が過ぎました
リマインド：期日が残り3日になると通知される　『タイトル』の期日が残り3日になりました

//URL
GET /notifications　通知一覧を表示
POST /notifications/{id}/read　通知が既読化される

//通知コマンド
sail artisan books:expired
sail artisan books:reminder

//cron
#
# Each task to run has to be defined through a single line
# indicating with different fields when the task will be run
# and what command to run for the task
#
# To define the time you can provide concrete values for
# minute (m), hour (h), day of month (dom), month (mon),
# and day of week (dow) or use '*' in these fields (for 'any').
#
# Notice that tasks will be started based on the cron's system
# daemon's notion of time and timezones.
#
# Output of the crontab jobs (including errors) is sent through
# email to the user the crontab file belongs to (unless redirected).
#
# For example, you can run a backup of all your user accounts
# at 5 a.m every week with:
# 0 5 * * 1 tar -zcf /var/backups/home.tgz /home/
#
# For more information see the manual pages of crontab(5) and cron(8)
#
# m h  dom mon dow   command
* * * * * cd /home/nanami/BookShelf/bookshelf-app && vendor/bin/sail artisan schedule:run >> /dev/null 2>&1

