# Symfony Image Gallery

### Configuration

```shell
git clone https://github.com/lesterdlb/symfony-image-gallery.git
cd symfony-image-gallery
# Build containers
make build
# Start containers
make start
# Create .env copy
cp cp www/html/app/.env.dist www/html/app/.env
# Install composer dependencies
make install
# Give upload folder write permissions
make uploads-permissions
# Start Symfony worker for RabbitMQ
make start-messenger-consumer
```
