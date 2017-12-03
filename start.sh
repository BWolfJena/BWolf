cd ./laradock
sudo docker-compose up -d
sleep 1
sudo docker-compose exec --user=laradock workspace composer install
sudo docker-compose exec --user=laradock workspace php artisan october:up
sudo docker-compose exec --user=laradock workspace php artisan october:mirror public/