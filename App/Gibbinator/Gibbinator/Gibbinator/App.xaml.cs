using System;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;
using Gibbinator.Views;
using Gibbinator.Data;
using System.IO;

[assembly: XamlCompilation(XamlCompilationOptions.Compile)]
namespace Gibbinator
{
    public partial class App : Application
    {
        static GibbinatorDatabase database;

        public App()
        {
            InitializeComponent();
            Syncfusion.Licensing.SyncfusionLicenseProvider.RegisterLicense("MzQ0MDhAMzEzNjJlMzMyZTMwWkRxZWVET1E0K1lhM3c5bHh4MzRwM2ZkbVl3VTFiT0UxbTdEdER2MFlBOD0=");
            MainPage = new MainPage();
        }

        public static GibbinatorDatabase Database
        {
            get
            {
                if (database == null)
                {
                    database = new GibbinatorDatabase(
                      Path.Combine(Environment.GetFolderPath(Environment.SpecialFolder.LocalApplicationData), "TodoSQLite.db3"));
                }
                return database;
            }
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
