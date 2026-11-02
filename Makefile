.PHONY: lint-dockerfile

## lint-dockerfile: assert that USER and ENTRYPOINT are present in both production Dockerfiles.
lint-dockerfile:
	@echo "Checking docker/Dockerfile for required directives..."
	@grep -E '^USER[[:space:]]+' docker/Dockerfile > /dev/null \
		|| (echo "ERROR: USER directive missing from docker/Dockerfile" && exit 1)
	@grep -E '^ENTRYPOINT' docker/Dockerfile > /dev/null \
		|| (echo "ERROR: ENTRYPOINT directive missing from docker/Dockerfile" && exit 1)
	@echo "Checking docker/Dockerfile-queue for required directives..."
	@grep -E '^USER[[:space:]]+' docker/Dockerfile-queue > /dev/null \
		|| (echo "ERROR: USER directive missing from docker/Dockerfile-queue" && exit 1)
	@grep -E '^ENTRYPOINT' docker/Dockerfile-queue > /dev/null \
		|| (echo "ERROR: ENTRYPOINT directive missing from docker/Dockerfile-queue" && exit 1)
	@echo "lint-dockerfile passed: USER and ENTRYPOINT are present in both Dockerfiles."
