using Gibbinator.Models;
using System;
using System.Collections.Generic;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace Gibbinator.Views
{
    [XamlCompilation(XamlCompilationOptions.Compile)]
    public partial class MenuPage : ContentPage
    {
        MainPage RootPage { get => Application.Current.MainPage as MainPage; }
        List<HomeMenuItem> menuItems;
        public MenuPage()
        {
            InitializeComponent();

            menuItems = new List<HomeMenuItem>
            {
                new HomeMenuItem {Id = MenuItemType.Schedule, Title="Stundenplan" },
                new HomeMenuItem {Id = MenuItemType.Upcoming, Title="Anstehendes" },
                new HomeMenuItem {Id = MenuItemType.Messages, Title="Mitteilungen" },
                new HomeMenuItem {Id = MenuItemType.Teachers, Title="Lehrerliste" },
                new HomeMenuItem {Id = MenuItemType.Calculator, Title="Notenrechner" },
                new HomeMenuItem {Id = MenuItemType.About, Title="Über uns" }
            };

            ListViewMenu.ItemsSource = menuItems;

            ListViewMenu.SelectedItem = menuItems[0];
            ListViewMenu.ItemSelected += async (sender, e) =>
            {
                if (e.SelectedItem == null)
                    return;

                var id = (int)((HomeMenuItem)e.SelectedItem).Id;
                await RootPage.NavigateFromMenu(id);
            };
        }
    }
}