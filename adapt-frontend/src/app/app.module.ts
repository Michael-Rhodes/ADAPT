import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { AppRoutingModule } from './app-routing.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatInputModule, MatPaginatorModule, MatProgressSpinnerModule, MatSortModule, MatTableModule } from "@angular/material";
import { AppComponent } from './app.component';
import { LogsComponent } from './logs/logs.component';
import { AboutComponent } from './about/about.component';
import { ChartsComponent } from './charts/charts.component';
import { ChartsModule } from 'ng2-charts';
import { BehaviorSubject} from 'rxjs';
import { OverviewComponent } from './overview/overview.component';

@NgModule({
  declarations: [
    AppComponent,
    LogsComponent,
    AboutComponent,
    ChartsComponent,
    OverviewComponent
    ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    AppRoutingModule,
    MatInputModule,
    MatPaginatorModule,
    MatProgressSpinnerModule,
    MatSortModule,
    MatTableModule,
    ChartsModule,
    
  ],
  providers: [],
  bootstrap: [
    AppComponent
  ]
})
export class AppModule { }
