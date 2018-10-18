using Gibbinator.Models;
using Gibbinator.ViewModels;
using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Input;
using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace Gibbinator.Views
{
	[XamlCompilation(XamlCompilationOptions.Compile)]
	public partial class TeacherDetailPage : ContentPage
    {
        TeacherDetailViewModel viewModel;

        public TeacherDetailPage(TeacherDetailViewModel viewModel)
        {
            InitializeComponent();
            
            BindingContext = this.viewModel = viewModel;
        }

        public TeacherDetailPage()
        {
            InitializeComponent();

            var teacher = new Teacher
            {
                Name = "Item 1",
                Surname = "This is an item description.",
                Email = "",
                Telephone = ""
            };

            viewModel = new TeacherDetailViewModel(teacher);
            BindingContext = viewModel;
        }


    }
}