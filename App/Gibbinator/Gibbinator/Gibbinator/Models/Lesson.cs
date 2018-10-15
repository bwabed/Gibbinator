using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.Models
{
    class Lesson
    {
        public int LessonId { get; set; }
        public Subject SubjectId { get; set; }
        public Teacher TeacherId { get; set; }
        public DateTime StartTime { get; set; }
        public DateTime EndTime { get; set; }
        public String Description { get; set; }
        public String Course { get; set; }
        public Room RoomId { get; set; }
        public List<Task> Tasks{ get; set; }
    }
}
