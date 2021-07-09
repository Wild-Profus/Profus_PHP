# initial image : choices are python slim, ubuntu or python
# https://pythonspeed.com/articles/faster-python/
# https://pythonspeed.com/articles/base-image-python-docker-images/
# 
FROM python:3.9.5-slim-buster as compile-image

# Good practice to combine update and install
# -y say yes to anything (no manual intervention required)
# RUN apt-get update && apt-get install -y --no-install-recommends \
#     build-essential \
#     gcc \
#     libpq-dev \
#     libffi-dev

# Every RUN line in the Dockerfile is a different process. 
# Running activate in a separate RUN has no effect on future RUN calls
# Activate set VIRTUAL_ENV(useless most of the time) and PATH.
RUN python -m venv /venv
# Make sure we use the virtualenv:
# ENV PATH="/venv/bin:$PATH" not required if only for first image
RUN PATH="/venv/bin:$PATH" pip install --upgrade pip

# https://stackoverflow.com/questions/34398632/docker-how-to-run-pip-requirements-txt-only-if-there-was-a-change
# If we copy all the folder instead of just the requirements.txt,
# the layer will be rebuilt every time any file change
COPY requirements.txt /code/
RUN PATH="/venv/bin:$PATH" pip install -r /code/requirements.txt


FROM python:3.9.5-slim-buster AS build-image
COPY --from=compile-image /venv /venv

COPY . /code/
WORKDIR /code

# Make sure we use the virtualenv:
ENV PATH="/venv/bin:$PATH"

# realtime terminal output https://stackoverflow.com/questions/59812009/what-is-the-use-of-pythonunbuffered-in-docker-file/59812588
ENV PYTHONUNBUFFERED="1"

# Django settings file parameters
ENV DJANGO_SETTINGS_MODULE config.settings.local

# CMD ["python", "manage.py", "runserver", "0.0.0.0:8000"]