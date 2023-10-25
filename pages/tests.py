from http import HTTPStatus
from django.test import SimpleTestCase

# Create your tests here.

class RobotsTxtTests(SimpleTestCase):
    def test_get(self):
        response = self.client.get("/robots.txt")

        assert response.status_code == HTTPStatus.OK
        assert response['content-type'] == "text/plain"

