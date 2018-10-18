using Gibbinator.Models;
using System;
using System.Collections.Generic;
using System.Threading.Tasks;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace Gibbinator.Views
{
    [XamlCompilation(XamlCompilationOptions.Compile)]
    public partial class MainPage : MasterDetailPage
    {
        Dictionary<int, NavigationPage> MenuPages = new Dictionary<int, NavigationPage>();
        public MainPage()
        {
            InitializeComponent();

            MasterBehavior = MasterBehavior.Popover;
            
            MenuPages.Add((int)MenuItemType.Upcoming, new NavigationPage(new UpcomingPage()));
            MenuPages.Add((int)MenuItemType.Messages, new NavigationPage(new MessagesPage()));
            MenuPages.Add((int)MenuItemType.Teachers, new NavigationPage(new TeacherPage()));
            MenuPages.Add((int)MenuItemType.Calculator, new NavigationPage(new CalculatorPage()));
        }

        public async Task NavigateFromMenu(int id)
        {
            if (!MenuPages.ContainsKey(id))
            {
                switch (id)
                {
                    case (int)MenuItemType.Schedule:
                        MenuPages.Add(id, new NavigationPage(new SchedulePage()));
                        break;
                    case (int)MenuItemType.Upcoming:
                        MenuPages.Add(id, new NavigationPage(new UpcomingPage()));
                        break;
                    case (int)MenuItemType.Messages:
                        MenuPages.Add(id, new NavigationPage(new MessagesPage()));
                        break;
                    case (int)MenuItemType.Teachers:
                        MenuPages.Add(id, new NavigationPage(new TeacherPage()));
                        break;
                    case (int)MenuItemType.Calculator:
                        MenuPages.Add(id, new NavigationPage(new CalculatorPage()));
                        break;
                    case (int)MenuItemType.About:
                        MenuPages.Add(id, new NavigationPage(new AboutPage()));
                        break;

                }
            }

            var newPage = MenuPages[id];

            if (newPage != null && Detail != newPage)
            {
                Detail = newPage;

                if (Device.RuntimePlatform == Device.Android)
                    await Task.Delay(100);

                IsPresented = false;
            }
        }
    }
}