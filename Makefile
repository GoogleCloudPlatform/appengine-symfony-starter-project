###> deploy to app engine ###
dev:
	app/console cache:warmup --no-debug --env=dev
	dev_appserver.py .
.PHONY: deploy-dev

deploy:
	app/console cache:warmup --no-debug --env=prod
	gcloud app deploy -q
.PHONY: deploy-dev
