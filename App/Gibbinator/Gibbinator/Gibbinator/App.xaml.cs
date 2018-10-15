using System;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;
using Gibbinator.Views;

[assembly: XamlCompilation(XamlCompilationOptions.Compile)]
namespace Gibbinator
{
    public partial class App : Application
    {

        public App()
        {
            Syncfusion.Licensing.SyncfusionLicenseProvider.RegisterLicense("MzQ0MDhAMzEzNjJlMzMyZTMwWkRxZWVET1E0K1lhM3c5bHh4MzRwM2ZkbVl3VTFiT0UxbTdEdER2MFlBOD0=");
            InitializeComponent();


            MainPage = new MainPage();
        }

        protected override void OnStart()
        {
            // Handle when your app starts
        }

        protected override void OnSleep()
        {
            // Handle when your app sleeps
        }

        protected override void OnResume()
        {
            // Handle when your app resumes
        }
    }
}
