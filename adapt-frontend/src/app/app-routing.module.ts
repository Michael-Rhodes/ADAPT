import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import { LogsComponent } from './logs/logs.component';
import { AboutComponent } from './about/about.component';
import { ChartsComponent } from './charts/charts.component';
import { OverviewComponent} from './overview/overview.component';

const routes: Routes = [
  { path: 'charts', component:ChartsComponent},
  { path: 'about', component:AboutComponent},
  { path: 'logs', component: LogsComponent},
  { path: 'overview', component: OverviewComponent},
  {path: '**', redirectTo: '/about'}
]; 

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})

export class AppRoutingModule { }
