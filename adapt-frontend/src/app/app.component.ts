import { Component } from '@angular/core';
import { GraphDataService } from './graphData.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
  providers: [ GraphDataService]
})
export class AppComponent {
  constructor(private api: GraphDataService) {
    this.api.callAPI();
  }
}
