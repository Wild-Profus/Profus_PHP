from django.contrib.auth import get_user_model
from django.test import TestCase

# Create your tests here.
class UserTests(TestCase):

    def test_create_user(self):
        User = get_user_model()
        user = User.objects.create_user(
            pseudo='will',
            email='will@mail.com',
            password='azerty123'
        )
        self.assertEqual(user.username, 'will')
        self.assertEqual(user.email, 'will@mail.com')
        self.assertTrue(user.is_active)
        self.assertFalse(user.is_staff)
        self.assertFalse(user.is_superuser)

    def test_create_superuser(self):
        User = get_user_model()
        user = User.objects.create_superuser(
            pseudo='superadmin',
            email='superadmin@mail.com',
            password='azerty123'
        )
        self.assertEqual(user.username, 'superadmin')
        self.assertEqual(user.email, 'superadmin@mail.com')
        self.assertTrue(user.is_active)
        self.assertTrue(user.is_staff)
        self.assertTrue(user.is_superuser)
