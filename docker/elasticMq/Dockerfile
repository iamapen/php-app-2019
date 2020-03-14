FROM java:8

# Create working space
RUN mkdir /var/elasticMQ
WORKDIR /var/elasticMQ

# Default port for SQS Local
EXPOSE 9324

# Get the package from Amazon
RUN wget -q https://s3-eu-west-1.amazonaws.com/softwaremill-public/elasticmq-server-0.15.5.jar

# Default command for image
ENTRYPOINT ["/usr/bin/java", "-Djava.library.path=.", "-jar", "elasticmq-server-0.15.5.jar"]
