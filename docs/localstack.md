# localstack

AWSのモック


aws configure
```bash
docker-compose exec localstack \
  aws configure --profile local

AWS Access Key ID [None]: dummy
AWS Secret Access Key [None]: dummy
Default region name [None]: ap-northeast-1
Default output format [None]: json
```

キューの作成
```bash
docker-compose exec localstack \
  aws --profile local --endpoint-url http://localhost:4576 \
  sqs create-queue --queue-name 'sample-jobs'

{
    "QueueUrl": "http://localhost:4576/queue/sample-jobs"
}
```

「sample」で始まるキュー一覧
```bash
docker-compose exec localstack \
  aws sqs list-queues --profile local --endpoint-url http://localhost:4576 \
  --queue-name-prefix sample

{
    "QueueUrls": [
        "http://localhost:4576/queue/sample-jobs"
    ]
}
```

受信可能なメッセージの件数
```bash
docker-compose exec localstack \
  aws sqs get-queue-attributes --profile local \
  --endpoint-url http://localhost:4576 \
  --queue-url http://localhost:4576/queue/sample-jobs \
  --attribute-names ApproximateNumberOfMessages

{
    "Attributes": {
        "ApproximateNumberOfMessages": "0"
    }
}
```

他で受信されて、一定時間アクティブでない件数
```bash
docker-compose exec localstack \
  aws sqs get-queue-attributes --profile local \ 
  --endpoint-url http://localhost:4576 \
  --queue-url http://localhost:4576/queue/sample-jobs \
  --attribute-names ApproximateNumberOfMessagesNotVisible

{
    "Attributes": {
        "ApproximateNumberOfMessagesNotVisible": "0"
    }
}
```

受信
```bash
docker-compose exec localstack \
  aws sqs receive-message \
  --profile local --endpoint-url http://localhost:4576 \
  --queue-url http://localhost:4576/queue/sample-jobs

{
    "Messages": [
        {
            "MessageId": "eaa9ffef-7136-4dc6-8c75-ec8c262593ca",
            "ReceiptHandle": "eaa9ffef-7136-4dc6-8c75-ec8c262593ca#6aacea64-a25f-40cd-bf09-5c6f0d54cd55",
            "MD5OfBody": "b0ccdcb86f696853a17362b6b1fa1de9",
            "Body": "{\"Name\":\"arare\",\"Like\":\"ncha\"}"
        }
    ]
}
```

publish
```bash
docker-compose exec localstack \
  aws sqs send-message \
  --profile local --endpoint-url http://localhost:4576 \
  --queue-url http://localhost:4576/queue/sample-jobs \
  --message-body '{"Name":"arare","Like":"ncha"}'

{
    "MD5OfMessageBody": "b0ccdcb86f696853a17362b6b1fa1de9",
    "MessageId": "eaa9ffef-7136-4dc6-8c75-ec8c262593ca"
}
```
