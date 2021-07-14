# THIS := $(realpath $(lastword $(MAKEFILE_LIST)))
# HERE := $(shell dirname $(THIS))


.PHONY: all

all: bannar install migrate note serve

# setup:
# 	@php -r "file_exists('.env') || copy('.env.example', '.env');"

install:
	@$(MAKE) composer
# 	@$(MAKE) key

composer:
	@composer install

# key:
# 	@php artisan key:generate

migrate:
	@php artisan migrate

serve:
	@npm install pm2@latest -g
	@pm2 start irt-worker.yaml
	@$(MAKE) note

note:
	@echo "\n======================================== [NOTE] ========================================"
	@echo "You're ready to go! IMO Rapid Transfer Agent Platform Setup successfully:					 "
	@echo "[*] Powered By: Colken Consult														 "
	@echo "[*] Developed By: Kenneth Osekhuemen"
	@echo "========================================================================================\n"

bannar:
	@echo "  _____ _____  __     __   __________ ___    __ "
	@echo " / ____/  __ \| |     | | / /| |____||   \   | |"
	@echo "| |    | |  | | |     | |/ / | |_____| |\ \  | |"
	@echo "| |    | |  | | |     |   /  | _____|| | \ \ | |"
	@echo "| |____|  __ || |_____| |\ \ | |_____| |  \ \| |"
	@echo "|\_____\_____/|______||_| \_\|______||_|   \___|"
	@echo "\n"
