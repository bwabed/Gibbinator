using Gibbinator.Data;
using Gibbinator.Models;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace Gibbinator.Views
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class LoginPage : ContentPage
	{
        public LoginPage()
        {
            InitializeComponent();
        }

        async void OnLoginButtonClicked(object sender, EventArgs e)
        {
            var classId = enteredClassId.Text;

            var isValid = AreCredentialsCorrect(classId);
            if (isValid)
            {
                App.IsUserLoggedIn = true;
                Application.Current.MainPage = new MainPage();
            }
            else
            {
                messageLabel.Text = "Login failed";
            }
        }

        bool AreCredentialsCorrect(string classId)
        {
            return Constants.TestClass == classId;
        }
    }
}