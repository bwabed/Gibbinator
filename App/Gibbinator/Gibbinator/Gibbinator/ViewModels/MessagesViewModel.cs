using Gibbinator.Models;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Diagnostics;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Gibbinator.ViewModels
{
    public class MessagesViewModel : BaseViewModel
    {
        public ObservableCollection<Message> Messages { get; set; }
        public Command LoadMessagesCommand { get; set; }

        public MessagesViewModel()
        {
            Title = "Mitteilungen";
            Messages = new ObservableCollection<Message>();
            LoadMessagesCommand = new Command(async () => await ExecuteLoadMessagesCommand());

        }

        async Task ExecuteLoadMessagesCommand()
        {
            if (IsBusy)
                return;

            IsBusy = true;

            try
            {
                Messages.Clear();
                var messages = await DataStoreMessage.GetTeachersAsync(true);
                foreach (var message in messages)
                {
                    Messages.Add(message);
                }
            }
            catch (Exception ex)
            {
                Debug.WriteLine(ex);
            }
            finally
            {
                IsBusy = false;
            }
        }
    }
}
