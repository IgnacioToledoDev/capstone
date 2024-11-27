import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  
  constructor() {}

  toggleDarkMode(isDarkMode: boolean) {
    document.body.classList.toggle('dark-theme', isDarkMode);
}
  
}
